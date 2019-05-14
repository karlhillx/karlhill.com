@setup
require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
try {
$dotenv->load();
$dotenv->required(['DEPLOY_PATH'])->notEmpty();
} catch ( Exception $e )  {
echo $e->getMessage();
}

$path = getenv('DEPLOY_PATH');
$slack = getenv('DEPLOY_SLACK_WEBHOOK');
$date = ( new DateTime )->format('Y-m-d_H:i:s');

{{-- Files and direcrtories that need permissions of 755 and www-data as owner --}}
$chmods = [
'app/storage',
'public',
];

{{-- All directories symlinked to the shared folder --}}
$symlinks = [
'storage/views'    => 'app/storage/views',
'storage/sessions' => 'app/storage/sessions',
'storage/logs'     => 'app/storage/logs',
'storage/cache'    => 'app/storage/cache',
];

function logMessage($message) {
return "echo '\033[32m" .$message. "\033[0m';\n";
}
@endsetup

@servers(['local' => '127.0.0.1'])

@macro('deploy')
startDeployment
runComposer
migrateDatabase
generateAssets
updateSymlinks
optimizeInstallation
blessDeployment
finishDeploy
@endmacro

@task('startDeployment')
{{ logMessage('🏃  Starting deployment...') }}
php artisan down
git reset --hard HEAD
git clean -df
git pull --force
@endtask

@task('runComposer')
{{ logMessage('🚚  Running Composer...') }}
composer global update
composer install --no-interaction --quiet --prefer-dist --optimize-autoloader --no-scripts --no-dev -q -o;
@endtask

@task('generateAssets')
{{ logMessage('🌅  Generating assets...') }}
npm run production
@endtask

@task('updateSymlinks')
{{ logMessage('🔗  Updating symlinks...') }}
@foreach($symlinks as $folder => $symlink)
    ln -s {{ $path }}/shared/{{ $folder }} {{ $release }}/{{ $symlink }}

    echo "Symlink has been set for {{ $symlink }}"
@endforeach
{{ logMessage('🔗  All symlinks have been set.') }}
@endtask

@task('migrateDatabase')
{{ logMessage('🙈  Migrating database...') }}
#php artisan migrate --force --no-interaction
@endtask

@task('chmod', ['on' => $on])
@foreach($chmods as $file)
    {{--
    chmod -R 755 {{ $release }}/{{ $file }}
    chmod -R g+s {{ $release }}/{{ $file }}
    chown -R www-data:www-data {{ $release }}/{{ $file };
    echo "Permissions have been set for {{ $file }}"
    --}}
@endforeach
@endtask

@task('optimizeInstallation')
{{ logMessage('✨  Optimizing installation...') }}
php artisan clear-compiled
@endtask

@task('blessDeployment')
{{ logMessage('🙏  Blessing deployment...') }}
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan view:cache
@endtask

@task('finishDeploy')
php artisan queue:restart --quiet
php artisan up
{{ logMessage('🚀  Deployment finished successfully!') }}
@endtask

{{--
@finished
	@slack($slack, '#deployments', "Deployment on {$server}: {$date} complete")
@endfinished
--}}
