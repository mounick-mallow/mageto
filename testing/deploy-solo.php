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
    $PROJECT_NAME=get('labels')['env'];

    if ($PROJECT_NAME == "brands-prod")
    {
            writeln(get('labels')['env']);
            run("rm -r {{release_or_current_path}}/app/etc/config.php");
            run("cp -rf {{release_or_current_path}}/app/design/frontend/LuxuryUnlimited/brands_labels/.deploy/config.php  {{release_or_current_path}}/app/etc/config.php");

    }
    if ($PROJECT_NAME == "solo-prod")
    {
            writeln(get('labels')['env']);
            run("rm -r {{release_or_current_path}}/app/etc/config.php");
            run("cp -rf {{release_or_current_path}}/app/design/frontend/LuxuryUnlimited/sololuxury/.deploy/config.php  {{release_or_current_path}}/app/etc/config.php");
    }
    if ($PROJECT_NAME == "avoir-chic-prod")
    {
            writeln(get('labels')['env']);
            run("rm -r {{release_or_current_path}}/app/etc/config.php");
            run("cp -rf {{release_or_current_path}}/app/design/frontend/LuxuryUnlimited/avoirchic/.deploy/config.php  {{release_or_current_path}}/app/etc/config.php");
    }
    if ($PROJECT_NAME == "veralusso-prod")
    {
            writeln(get('labels')['env']);
            run("rm -r {{release_or_current_path}}/app/etc/config.php");
            run("cp -rf {{release_or_current_path}}/app/design/frontend/LuxuryUnlimited/veralusso/.deploy/config.php  {{release_or_current_path}}/app/etc/config.php");
    }
    if ($PROJECT_NAME == "suvandnat-prod")
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

task('magento:deploy:assets', function() {
    run("cd {{release_or_current_path}} && sudo -u www-data php bin/magento setup:static-content:deploy -s standard -j16 -f --theme Magento/backend --theme LuxuryUnlimited/sololuxury --theme LuxuryUnlimited/sololuxury_rtl");
    echo "cd {{release_or_current_path}} && sudo -u www-data php bin/magento setup:static-content:deploy -s standard -j16 -f --theme Magento/backend --theme LuxuryUnlimited/sololuxury --theme LuxuryUnlimited/sololuxury_rtl";
});

after('deploy:clear_paths', 'deploy:update-meta');
after('deploy:update-meta', 'deploy:magento-composer-install');
after('deploy:magento-composer-install', 'deploy:copyconfig');
after('deploy:copyconfig', 'deploy:magento-setup-upgrade');
after('deploy:magento-setup-upgrade', 'deploy:magento-compile');
after('deploy:magento-compile', 'deploy:magento-autoload');
//after('deploy:magento-autoload', 'magento:deploy:assets');
after('magento:deploy:assets', 'deploy:magento-js-bundle');
after('deploy:magento-js-bundle', 'deploy:indexes');
after('deploy:indexes', 'deploy:magento-cache-flush');
after('magento:cache:flush', 'deploy:fpm:restart');
after('deploy:fpm:restart', 'deploy:varnish:restart');

// Disabled Tasks

task('magento:compile')->disable();
task('magento:sync:content_version')->disable();
//task('magento:deploy:assets')->disable();
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

host('brands-prod-1-1')
    ->set('hostname', '89.116.25.24')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'brands-prod',
    ]);

host('brands-prod-1-2')
    ->set('hostname', '89.116.25.24')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'brands-prod',
    ]);

host('brands-prod-2-1')
    ->set('hostname', '31.220.94.122')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'brands-prod',
    ]);

host('brands-prod-2-2')
    ->set('hostname', '31.220.94.122')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'brands-prod',
    ]);



host('brands-stage-1-1')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-labels-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'brands-stage',
    ]);

host('brands-stage-1-2')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-labels-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'brands-stage',
    ]);


// Hosts Sololuxury

host('solo-prod-1-1')
    ->set('hostname', '173.249.14.153')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
    ]);

host('solo-prod-1-2')
    ->set('hostname', '173.249.14.153')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
    ]);

host('solo-prod-2-1')
    ->set('hostname', '84.46.240.184')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
    ]);

host('solo-prod-2-2')
    ->set('hostname', '84.46.240.184')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
    ]);

host('solo-prod-3-1')
    ->set('hostname', '173.249.14.153')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-3-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
    ]);

host('solo-prod-3-2')
    ->set('hostname', '173.249.14.153')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-3-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
    ]);

host('solo-prod-4-1')
    ->set('hostname', '84.46.240.184')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-4-1')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
    ]);

host('solo-prod-4-2')
    ->set('hostname', '84.46.240.184')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-4-2')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
    ]);

host('solo-qa-1-1')
    ->set('hostname', '85.208.51.101')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/qa-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-qa',
    ]);

host('solo-qa-1-2')
    ->set('hostname', '85.208.51.101')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/qa-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-qa',
    ]);

host('solo-stage-1-1')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/sololuxury-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-stage',
    ]);

host('solo-stage-1-2')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/sololuxury-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-stage',
    ]);


host('avoir-chic-prod-1-1')
    ->set('hostname', '184.174.32.145')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-prod',
    ]);

host('avoir-chic-prod-1-2')
    ->set('hostname', '184.174.32.145')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-prod',
    ]);

host('avoir-chic-prod-2-1')
    ->set('hostname', '184.174.32.149')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-prod',
    ]);

host('avoir-chic-prod-2-2')
    ->set('hostname', '184.174.32.149')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-prod',
    ]);



host('avoir-chic-stage-1-1')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home2/avoir-chic-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-stage',
    ]);

host('avoir-chic-stage-1-2')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home2/avoir-chic-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-stage',
    ]);

host('veralusso-prod-1-1')
    ->set('hostname', '184.174.32.43')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-prod',
    ]);

host('veralusso-prod-1-2')
    ->set('hostname', '184.174.32.43')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-prod',
    ]);

host('veralusso-prod-2-1')
    ->set('hostname', '184.174.32.58')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-prod',
    ]);

host('veralusso-prod-2-2')
    ->set('hostname', '184.174.32.58')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-prod',
    ]);



host('veralusso-stage-1-1')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-stage',
    ]);

host('veralusso-stage-1-2')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-stage',
    ]);

host('suvandnat-prod-1-1')
    ->set('hostname', '38.242.235.10')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'suvandnat-prod',
    ]);

host('suvandnat-prod-1-2')
    ->set('hostname', '38.242.235.10')
    ->set('remote_user', 'root')
    ->set('port', '22480')
    ->set('deploy_path', '/home/prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'suvandnat-prod',
    ]);

host('suvandnat-prod-2-1')
    ->set('hostname', '38.242.235.6')
    ->set('remote_user', 'root')
    ->set('port', '22480')
    ->set('deploy_path', '/home/prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'suvandnat-prod',
    ]);

host('suvandnat-prod-2-2')
    ->set('hostname', '38.242.235.6')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'suvandnat-prod',
    ]);


host('suvandnat-stage-1-1')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home2/suvandnat-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'suvandnat-stage',
    ]);

host('suvandnat-stage-1-2')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home2/suvandnat-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'suvandnat-stage',
    ]);


/*
host('qa-1-1')
    ->set('hostname', '65.21.243.9')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/qa-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->setLabels([
        'env' => 'qa',
    ]);

host('qa-1-2')
    ->set('hostname', '65.21.243.9')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/qa-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->setLabels([
        'env' => 'qa',
    ]);
 */

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

