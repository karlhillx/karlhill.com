@servers(['localhost' => '127.0.0.1'])

@setup
    $chmods = ['storage','bootstrap/cache'];

    function logMessage($message) {
        return "echo '\033[32m" .$message. "\033[0m';\n";
    }
@endsetup

@servers(['local' => '127.0.0.1'])

@story('deploy')
    startDeployment
    runComposer
    generateAssets
    updatePermissions
    optimizeInstall
    finishDeploy
@endstory

@task('startDeployment')
    {{ logMessage('🚀 Starting deployment...') }}
    php artisan down
@endtask

@task('gitPull', ['on' => 'localhost', 'confirm' => true])
    {{ logMessage('🛒 Getting the latest from Git repo...') }}
    git pull
@endtask

@task('runComposer')
    {{ logMessage('🏃 Running Composer...') }}
    composer dump-autoload
    composer global update
    composer update
    composer install --optimize-autoloader --no-dev
@endtask

@task('generateAssets')
    {{ logMessage('🌅 Generating assets...') }}
    npm install
    npm update
    #npm run production
@endtask

@task('migrateDatabase')
    {{ logMessage('🙈 Migrating database...') }}
    #php artisan migrate --force --no-interaction
@endtask

@task('seedDatabase', ['on' => 'localhost', 'confirm' => true])
    {{ logMessage('🙈 Seeding database...') }}
    php artisan db:seed --force
@endtask

@task('updatePermissions')
    {{ logMessage('🤝 Updating permissions...') }}
    @foreach($chmods as $dir)
        chmod 755 {{ $dir }}
        chmod g+w {{ $dir }}
        echo "Permissions have been set for {{ $dir }}"
    @endforeach
@endtask

@task('optimizeInstall')
    {{ logMessage("✨ Optimizing installation...") }}
    php artisan clear-compiled;
    php artisan optimize
@endtask

@task('finishDeploy')
    {{ logMessage('🙏 Blessing deployment...') }}
    php artisan up
    {{ logMessage('😋 Deployment finished successfully!') }}
@endtask

@error
    echo "An error has occurred in this deployment.";
@enderror

{{--
@finished
	@slack($slack, '#deployments', "Deployment on {$server}: {$date} complete")
@endfinished
--}}
