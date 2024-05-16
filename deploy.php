<?php
namespace Deployer;

// Remote Servers
import('deploy_hosts_config.yml');

// Config
set('repository', 'git@github.com:benwaiuk/jetworks-dev.git');

set('php_fpm_version', '8.1');
set('php_fpm_service', 'php8.1-fpm-sp');

//Should we reload PHP-FPM after deploying updates?
set('php_fpm_reload', true );

// Set the Unix command used to set directory permissions
//set('writable_mode', 'chown');

// Allows you to add passphrases for keys or add known hits when using git
set('git_tty', true);

// Laravel occasionally has long running tasks during deployment, so let's up the timeout...
set('default_timeout', 600);

set('keep_releases', 3);

// Project name
set('application', 'Laravel for panels.jetworks145.com');


set('release_name', function () {
    return (string) run('date +"%s"');
});

// Sometimes we need to interact with the remote shell to enter passwords, keys, etc.
set('git_tty', true);

task('deploy_first', [
    'deploy:prepare',
    'deploy:vendors',
    'deploy_vendors_fix',
    'deploy:publish',
//    'reboot_laravel',
//    'reset_permissions',
    'maybe-reload-php-fpm',
]);

task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'deploy_vendors_fix',
    'artisan:storage:link',
    'artisan:config:cache',
    'artisan:route:cache',
    'artisan:view:cache',
    'artisan:event:cache',
    'artisan:migrate',
    'build_npm',
    'build_frontend',
    'deploy:publish',
    'reboot_laravel',
//    'reset_permissions',
    'maybe-reload-php-fpm',
]);

task('build_npm', function () {
    cd('{{release_or_current_path}}');
    run('npm install');
});

task('build_frontend', function () {
    cd('{{release_or_current_path}}');
    run('npm run build');
});

task('deploy_vendors_fix', function () {
    cd('{{release_or_current_path}}');
    run('composer install {{composer_options}}');
});

task('reboot_laravel', function () {
    run('cd {{release_path}} && php artisan cache:clear && php artisan config:clear && php artisan config:cache && php artisan view:clear && php artisan clear-compiled && php artisan optimize && php artisan route:cache');
    writeln('Laravel Config and Cache cleared');
});

task('maybe-reload-php-fpm', function () {
    if( get('php_fpm_reload') ){
        writeln('Reloading PHP-FPM');
        set('remote_user', 'deployer');
        run('echo "" | sudo -S service {{php_fpm_service}} restart');
    }else{
        writeln('Not reloading PHP-FPM');
    }
});

task('reset_permissions', function () {
    run('chown -R {{http_user}} {{release_path}}');
    writeln('Reset folder permissions');
});

after('deploy:failed', 'deploy:unlock');
