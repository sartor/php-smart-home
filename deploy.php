<?php

/**
 * If need migrations:
 * php deployer.phar deploy dev
 * php deployer.phar deploy prod
 *
 * If not need migrations
 * php deployer.phar deploy-wo-migrate dev
 * php deployer.phar deploy-wo-migrate prod
 */

require 'recipe/common.php';

env('bin/composer', function () {
    run("cd {{release_path}} && curl -sS https://getcomposer.org/installer | {{bin/php}}");
    $composer = '{{bin/php}} {{release_path}}/composer.phar';
    run("$composer config --global github-oauth.github.com c194c393ad1c6bfa19d7efc2ed62d993c6abacfd");
    run("$composer global require fxp/composer-asset-plugin --prefer-dist --no-plugins");
    return $composer;
});

set('repository', '~/veterok.sartorua.com/git');

set('copy_dirs', [
    'vendor',
]);

set('copy_shared_files', [
    'yii',
    'web/index.php',
    'config/db.php',
]);

set('shared_dirs', [ // Must be set writable manually
    'runtime',
    'web/assets',
]);

server('sartorua', 'sartorua.com')
    ->user('sartor')
    ->forwardAgent()
    ->stage('prod')
    ->env('deploy_path', '~/veterok.sartorua.com')
    ->env('branch', 'master');

task('deploy:copy_shared_files', function () {
    $files = get('copy_shared_files');
    foreach ($files as $file) {
        $path = "{{deploy_path}}/shared/$file";
        run("cp -pf $path {{release_path}}");
    }
});

task('deploy:migrate', function () {
    run('{{bin/php}} {{release_path}}/yii migrate up --interactive=0');
})->desc('Run migrations, crontab and flush cache');

task('cache', function () {
    run('{{bin/php}} {{release_path}}/yii cache/flush-all');
});

task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:copy_dirs',
    'deploy:copy_shared_files',
    'deploy:vendors',
    'deploy:migrate',
    'cache',
    'deploy:symlink',
    'cleanup',
])->desc('Deploy project with migrations');

after('deploy', 'success');

task('deploy-wo-migrate', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:copy_dirs',
    'deploy:copy_shared_files',
    'deploy:vendors',
    'cache',
    'deploy:symlink',
    'cleanup',
])->desc('Deploy project without migrations');

after('deploy-wo-migrate', 'success');