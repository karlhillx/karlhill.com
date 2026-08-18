<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AgentPacketController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ClientSiteController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\LlmsTxtController;
use App\Http\Controllers\MachineAssetController;
use App\Http\Controllers\NowController;
use App\Http\Controllers\PushController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WebmentionController;
use App\Http\Controllers\WorkController;
use App\Support\PageMeta;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

// Accessibility fixtures — only registered when A11Y_FIXTURES=true (CI).
if (config('site.a11y_fixtures')) {
    Route::get('/__a11y/contact-errors', function () {
        $errors = new ViewErrorBag;
        $errors->put('default', new MessageBag([
            'name' => ['The name field is required.'],
            'email' => ['The email field must be a valid email address.'],
            'message' => ['The message field must be at least 10 characters.'],
        ]));
        View::share('errors', $errors);
        request()->session()->flashInput([
            'name' => '',
            'email' => 'not-an-email',
            'message' => 'too short',
        ]);

        return response()
            ->view('a11y.contact-errors', [
                'meta' => PageMeta::a11yContactErrors(),
            ])
            ->header('Cache-Control', 'no-store, private');
    })->name('a11y.contact-errors');
}

// HTML pages: the site is effectively static (flat-file blog, cached GitHub
// data) so a short public TTL plus an ETag lets browsers and any future CDN
// revalidate cheaply (304s) without serving stale content.
Route::middleware('cache.headers:public;max_age=300;etag')->group(function (): void {
    Route::get('/', HomeController::class)->name('home');
    Route::get('/work/tag/{tag}', [WorkController::class, 'tag'])
        ->where('tag', '[a-z0-9-]+')
        ->name('work.tag');
    Route::get('/work', [WorkController::class, 'index'])->name('work');
    Route::get('/work/{slug}', [WorkController::class, 'show'])
        ->where('slug', '[a-z0-9-]+')
        ->name('work.show');
    Route::get('/about', AboutController::class)->name('about');
    Route::get('/now', NowController::class)->name('now');
    Route::get('/resume', ResumeController::class)->name('resume');
    Route::get('/kit', KitController::class)->name('kit');

    // Client staging — static sites under /clients/{slug}/ (noindex, not in nav/sitemap).
    Route::get('/clients', [ClientSiteController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}/{path?}', [ClientSiteController::class, 'show'])
        ->where([
            'client' => '[A-Za-z0-9][A-Za-z0-9.-]*',
            'path' => '.*',
        ])
        ->name('clients.show');

    Route::get('/blog/tag/{tag}', [BlogController::class, 'tag'])
        ->where('tag', '[a-z0-9-]+')
        ->name('blog.tag');
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])
        ->where('slug', '[a-z0-9-]+')
        ->name('blog.show');
});

// Contact form: not cacheable (POST), rate-limited to blunt spam/abuse.
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

// A fresh CSRF token for the contact form. Because the home page is publicly
// cacheable, its embedded token could be stale (or absent, if a shared CDN
// serves a visitor who never hit the origin). This endpoint is never cached,
// so hitting it both starts a session and returns a token that matches it.
Route::get('/csrf-token', fn () => response()
    ->json(['token' => csrf_token()])
    ->header('Cache-Control', 'no-store, private'))
    ->name('csrf-token');

// Machine-readable feeds change less often — cache them for an hour.
Route::middleware('cache.headers:public;max_age=3600;etag')->group(function (): void {
    Route::get('/feed.xml', [FeedController::class, 'atom'])->name('feed');
    Route::get('/feed.json', [FeedController::class, 'json'])->name('feed.json');
    Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
    Route::get('/llms.txt', [LlmsTxtController::class, 'index'])->name('llms');
    Route::get('/llms-full.txt', [LlmsTxtController::class, 'full'])->name('llms.full');
    Route::get('/api/site.json', [AgentPacketController::class, 'site'])->name('api.site');
    Route::get('/api/commands.json', [AgentPacketController::class, 'commands'])->name('api.commands');
    Route::get('/.well-known/mcp.json', [AgentPacketController::class, 'mcp'])->name('well-known.mcp');
    Route::get('/api/credentials.json', [MachineAssetController::class, 'credentials'])->name('api.credentials');
});

Route::middleware('cache.headers:public;max_age=86400;etag')->group(function (): void {
    Route::get('/dict/html-shell.dat', [MachineAssetController::class, 'dictionary'])->name('dict.shell');
});

Route::post('/webmention', [WebmentionController::class, 'store'])
    ->middleware('throttle:20,1')
    ->name('webmention.store');

Route::post('/push/subscribe', [PushController::class, 'subscribe'])
    ->middleware('throttle:10,1')
    ->name('push.subscribe');
Route::post('/push/unsubscribe', [PushController::class, 'unsubscribe'])
    ->middleware('throttle:10,1')
    ->name('push.unsubscribe');

Route::post('/report', [ReportingController::class, 'store'])
    ->middleware('throttle:60,1')
    ->name('report.store');
