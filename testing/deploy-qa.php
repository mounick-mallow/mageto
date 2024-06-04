<?php

namespace Deployer;

require 'recipe/magento2.php';

// Config

set('repository', 'git@github.com:ludxb/brands-labels.git');
set('keep_releases', 3);

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

desc("Restarts PHP-FPM. I've tried to avoid it, but it looks like I cannot");

task('deploy:fpm:restart', function() {
    return 'service php8.1-fpm restart';
});

task('deploy:varnish:restart', function() {
    return 'service varnish restart';
});

task('deploy:indexes', function() {
    run("{{bin/php}} {{bin/magento}} index:reset");
    run("{{bin/php}} {{bin/magento}} index:reindex");
    return 'php bin/magento index:reindex';
});

task('deploy:copyconfig', function() {
    $PROJECT_NAME=get('labels')['proj'];

    if ($PROJECT_NAME == "brands-qa")
    {
            writeln(get('labels')['env']);
            run("rm -r {{release_or_current_path}}/app/etc/config.php");
            run("cp -rf {{release_or_current_path}}/app/design/frontend/LuxuryUnlimited/brands_labels/.deploy/config.php  {{release_or_current_path}}/app/etc/config.php");
    }
    if ($PROJECT_NAME == "solo-qa")
    {
            writeln(get('labels')['env']);
            run("rm -r {{release_or_current_path}}/app/etc/config.php");
            run("cp -rf {{release_or_current_path}}/app/design/frontend/LuxuryUnlimited/sololuxury/.deploy/config.php  {{release_or_current_path}}/app/etc/config.php");
    }
    if ($PROJECT_NAME == "avoir-chic-qa")
    {
            writeln(get('labels')['env']);
            run("rm -r {{release_or_current_path}}/app/etc/config.php");
            run("cp -rf {{release_or_current_path}}/app/design/frontend/LuxuryUnlimited/avoirchic/.deploy/config.php  {{release_or_current_path}}/app/etc/config.php");
    }
    if ($PROJECT_NAME == "veralusso-qa")
    {
            writeln(get('labels')['env']);
            run("rm -r {{release_or_current_path}}/app/etc/config.php");
            run("cp -rf {{release_or_current_path}}/app/design/frontend/LuxuryUnlimited/veralusso/.deploy/config.php  {{release_or_current_path}}/app/etc/config.php");
    }
    if ($PROJECT_NAME == "suvandnat-qa")
    {
            writeln(get('labels')['env']);
            run("rm -r {{release_or_current_path}}/app/etc/config.php");
            run("cp -rf {{release_or_current_path}}/app/design/frontend/LuxuryUnlimited/suvandnat/.deploy/config.php  {{release_or_current_path}}/app/etc/config.php");
    }
    return 'php bin/magento test';
});

task('deploy:magento-composer-install', function() {
    run("cd {{release_or_current_path}}/{{magento_dir}} && {{bin/composer}} install");
});


task('deploy:magento-setup-upgrade', function() {
    run("cd {{release_or_current_path}} && bin/magento setup:upgrade -n");
});


task('deploy:magento-compile', function() {
    run("cd {{release_or_current_path}} && bin/magento setup:di:compile -n");
});


task('deploy:magento-autoload', function() {
    run("cd {{release_or_current_path}}/{{magento_dir}} && {{bin/composer}} dump-autoload -o --apcu");
});


task('deploy:magento-content-deploy', function() {
    run("cd {{release_or_current_path}} && bin/magento setup:static-content:deploy --no-parent -f --jobs=$(nproc) -n ");
});

task('deploy:magento-cache-flush', function() {
    run("cd {{release_or_current_path}} && bin/magento cache:flush");
});

task('deploy:magento-js-bundle', function() {
    run("cd {{release_or_current_path}} && magepack bundle");
});

task('deploy:update-meta', function() {
    run("cd {{release_or_current_path}}/{{magento_dir}} && {{bin/composer}} update ludxb/magento-meta-package --no-plugins");
});



after('deploy:clear_paths', 'deploy:update-meta');
after('deploy:update-meta', 'deploy:magento-composer-install');
after('deploy:magento-composer-install', 'deploy:copyconfig');
after('deploy:copyconfig', 'deploy:magento-setup-upgrade');
after('deploy:magento-setup-upgrade', 'deploy:magento-compile');
after('deploy:magento-compile', 'deploy:magento-autoload');
after('magento:deploy:assets', 'deploy:magento-js-bundle');
after('deploy:magento-js-bundle', 'deploy:indexes');
after('deploy:indexes', 'deploy:magento-cache-flush');
after('magento:cache:flush', 'deploy:fpm:restart');
after('deploy:fpm:restart', 'deploy:varnish:restart');

// Disabled Tasks

task('magento:compile')->disable();
task('magento:sync:content_version')->disable();
task('magento:maintenance:enable-if-needed')->disable();
task('magento:maintenance:enable')->disable();
task('magento:maintenance:enable')->disable();
task('magento:maintenance:enable')->disable();
task('magento:maintenance:enable')->disable();
task('magento:maintenance:enable')->disable();
task('magento:maintenance:enable')->disable();
task('magento:maintenance:enable')->disable();
task('magento:maintenance:enable')->disable();
task('magento:config:import')->disable();
task('magento:upgrade:db')->disable();
task('magento:maintenance:disable')->disable();
task('magento:cache:flush')->disable();






set('env', [
    'COMPOSER_ALLOW_SUPERUSER' => 1,
    'CURLOPT_CONNECTTIMEOUT' => 5000,
    'CURLOPT_CONNECTTIMEOUT' => 5000
]);

// Hosts Brands and labels

host('suvandnat-qa-1-1')
    ->set('hostname', '85.208.51.101')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/suvandnat-qa-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'qa',
        'proj' => 'suvandnat-qa',
    ]);

host('veralusso-qa-1-1')
    ->set('hostname', '85.208.51.101')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-qa-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'qa',
        'proj' => 'veralusso-qa',
    ]);
    
host('avoir-chic-qa-1-1')
    ->set('hostname', '85.208.51.101')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/avoir-chic-qa-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'qa',
        'proj' => 'avoir-chic-qa',
    ]);

host('solo-prod-qa-1-1')
    ->set('hostname', '85.208.51.101')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/solo-prod-qa-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'qa',
        'proj' => 'solo-prod-qa',
    ]);

host('brands-qa-1-1')
    ->set('hostname', '85.208.51.101')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-qa-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'qa',
        'proj' => 'brands-qa',
    ]);

// Hooks

after('deploy:failed', 'deploy:unlock');

desc('Fix elasticsearch memory settings');
task('fix:elasticsearch', function () {
    run("sed -i 's/Xms1g/Xms4g/' /etc/elasticsearch/jvm.options");
    run("sed -i 's/Xmx1g/Xmx4g/' /etc/elasticsearch/jvm.options");
    run("cd /usr/share/elasticsearch && bin/elasticsearch-plugin install analysis-phonetic");
    run("cd /usr/share/elasticsearch && bin/elasticsearch-plugin install analysis-icu");
    run('service elasticsearch restart');
})->oncePerNode();

