<?php

use App\Http\Controllers\AgentPacketController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\LlmsTxtController;
use App\Http\Controllers\MachineAssetController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
| Public, cacheable machine-readable GETs. Registered outside the web
| middleware group so they do not start a session or set CSRF cookies.
*/

Route::middleware('cache.headers:public;max_age=3600;etag')->group(function (): void {
    Route::get('/feed.xml', [FeedController::class, 'atom'])->name('feed');
    Route::get('/feed.json', [FeedController::class, 'json'])->name('feed.json');
    Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
    Route::get('/llms.txt', [LlmsTxtController::class, 'index'])->name('llms');
    Route::get('/llms-full.txt', [LlmsTxtController::class, 'full'])->name('llms.full');
    Route::get('/api/site.json', [AgentPacketController::class, 'site'])->name('api.site');
    Route::get('/api/commands.json', [AgentPacketController::class, 'commands'])->name('api.commands');
    Route::get('/.well-known/mcp.json', [AgentPacketController::class, 'mcp'])->name('well-known.mcp');
    Route::get('/.well-known/agent-card.json', [AgentPacketController::class, 'agentCard'])->name('well-known.agent-card');
    Route::get('/.well-known/agent.json', [AgentPacketController::class, 'agentCard'])->name('well-known.agent');
    Route::get('/api/credentials.json', [MachineAssetController::class, 'credentials'])->name('api.credentials');
});

Route::middleware('cache.headers:public;max_age=86400;etag')->group(function (): void {
    Route::get('/dict/html-shell.dat', [MachineAssetController::class, 'dictionary'])->name('dict.shell');
});
