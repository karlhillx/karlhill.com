@setup
    $path = getenv('DEPLOY_PATH');
    $slack = getenv('DEPLOY_SLACK_WEBHOOK');

    $chmods = [
        'storage',
        'bootstrap/cache',
    ];

    function logMessage($message) {
    return "echo '\033[32m" .$message. "\033[0m';\n";
    }
@endsetup

@servers(['local' => '127.0.0.1'])

@story('deploy')
    startDeployment
    runComposer
    migrateDatabase
    generateAssets
    blessDeployment
    finishDeploy
@endstory

@task('startDeployment')
    {{ logMessage('🚀 Starting deployment...') }}
    php artisan down
@endtask

@task('runComposer')
    {{ logMessage('🏃 Running Composer...') }}
    composer global update
    composer install --optimize-autoloader --no-dev
    composer dump-autoload
@endtask

@task('generateAssets')
    {{ logMessage('🌅  Generating assets...') }}
    npm install
    npm run prod
@endtask

@task('migrateDatabase')
    {{ logMessage('🙈  Migrating database...') }}
    #php artisan migrate --force --no-interaction
@endtask

@task('updatePermissions')
@endtask

@task('blessDeployment')
    {{ logMessage('🙏  Blessing deployment...') }}
    php artisan optimize:clear
@endtask

@task('finishDeploy')
    php artisan queue:restart --quiet
    php artisan up
    {{ logMessage('Deployment finished successfully!') }}
@endtask

{{--
@finished
	@slack($slack, '#deployments', "Deployment on {$server}: {$date} complete")
@endfinished
--}}
