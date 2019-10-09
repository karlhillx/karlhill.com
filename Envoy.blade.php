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

@macro('deploy')
    startDeployment
    runComposer
    migrateDatabase
    generateAssets
    optimizeInstallation
    blessDeployment
    finishDeploy
@endmacro

@task('startDeployment')
    {{ logMessage('🚀 Starting deployment...') }}
    php artisan down
@endtask

@task('runComposer')
    {{ logMessage('🏃 Running Composer...') }}
    composer global update
    composer dump-autoload
    composer install --no-interaction --quiet --prefer-dist --optimize-autoloader --no-scripts --no-dev -q -o;
@endtask

@task('generateAssets')
    {{ logMessage('🌅  Generating assets...') }}
    npm install
    npm run production
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
    {{ logMessage('Deployment finished successfully!') }}
@endtask

{{--
@finished
	@slack($slack, '#deployments', "Deployment on {$server}: {$date} complete")
@endfinished
--}}
