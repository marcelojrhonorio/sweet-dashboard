<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'vendor/deployer/recipes/recipe/slack.php';

// Project name
set('application', 'Sweetmedia');

// Project repository
set('repository', 'git@gitlab.com:sweetbonus/dashboard.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

set('slack_title', function () {
    return get('application', 'Project');
});

set('slack_webhook', 'https://hooks.slack.com/services/TBMUS0TC0/nG26ty2XIScgzHkNTbU7M0uU');
set('slack_text', '_{{user}}_ deploying `{{branch}}` to *{{target}}*');
set('slack_success_text', 'Deploy to *{{target}}* successful');
set('slack_failure_text', 'Deploy to *{{target}}* failed');
// Color of attachment
set('slack_color', '#4d91f7');
set('slack_success_color', '{{slack_color}}');
set('slack_failure_color', '#ff0909');

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);

// Hosts
host('uat-sweetmedia.com.br')
    ->user('sweet')
    ->identityFile('~/.ssh/id_rsa')
    ->set('branch', 'develop')
    ->set('deploy_path', '/var/www/html/uat-sweetmedia.com.br');

before('deploy', 'slack:notify');

// Tasks
task('build', function () {
    run('cd {{release_path}} && build');
});

task('reload:php-fpm', function () {
    run('sudo service php7.2-fpm restart');
    run('sudo service nginx restart');
    run('/usr/bin/recache_apps.sh');
});

task('deploy:done', [
    'artisan:view:clear',
    'artisan:cache:clear',
    'artisan:config:cache',
    'artisan:queue:restart',
    'artisan:optimize',
    'reload:php-fpm',
    'cleanup',
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

// before('deploy:symlink', 'artisan:migrate');

// Restart queues after deploy a new release.

after('deploy', 'deploy:done');

after('rollback', 'deploy:done');

after('success', 'slack:notify:success');

after('deploy:failed', 'slack:notify:failure');
