<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'the_where_what');

// Project repository
set('repository', 'https://github.com/gerritsxd/TheWhereWhatNew.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);
set('writable_use_sudo', true);

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);



// Hosts

host('thewherewhat.com')
    ->user('deployer')
    ->set('deploy_path', '/var/www/thewherewhat');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

