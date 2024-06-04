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

task('deploy:magento-react-checkout-install', function() {
    $PROJECT_THEME=get('labels')['theme'];
    run("cd {{release_or_current_path}}/{{magento_dir}}/vendor/ludxb/magento-meta-package/src/module-shellpea-reactcheckout/themes/$PROJECT_THEME/reactapp/ &&  npm install && npm run build && cp {{release_or_current_path}}/{{magento_dir}}/vendor/ludxb/magento-meta-package/src/module-shellpea-reactcheckout/themes/$PROJECT_THEME/view/frontend/web/js/react-checkout.js {{release_or_current_path}}/{{magento_dir}}/app/design/frontend/LuxuryUnlimited/$PROJECT_THEME/Shellpea_ReactCheckout/web/js/react-checkout.js");
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
    $PROJECT_THEME=get('labels')['theme'];
    run("cd {{release_or_current_path}} && sudo -u www-data php bin/magento setup:static-content:deploy -s standard -j16 -f");
});

after('deploy:clear_paths', 'deploy:update-meta');
after('deploy:update-meta', 'deploy:magento-composer-install');
after('deploy:magento-composer-install', 'deploy:copyconfig');
after('deploy:copyconfig', 'deploy:magento-setup-upgrade');
after('deploy:magento-setup-upgrade', 'deploy:magento-react-checkout-install');
after('deploy:magento-react-checkout-install', 'deploy:magento-compile');
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
        'theme' => 'brands_labels',
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
        'theme' => 'brands_labels',
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
        'theme' => 'brands_labels',
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
        'theme' => 'brands_labels',
    ]);

host('brands-prod-3-1')
    ->set('hostname', '89.116.25.24')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-3-1')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'brands-prod',
        'theme' => 'brands_labels',
    ]);

host('brands-prod-3-2')
    ->set('hostname', '89.116.25.24')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-3-2')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'brands-prod',
        'theme' => 'brands_labels',
    ]);


host('brands-prod-4-1')
    ->set('hostname', '31.220.94.122')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-4-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'brands-prod',
        'theme' => 'brands_labels',
    ]);

host('brands-prod-4-2')
    ->set('hostname', '31.220.94.122')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-4-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'brands-prod',
        'theme' => 'brands_labels',
    ]);



host('brands-stage-1-1')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-labels-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'brands-stage',
        'theme' => 'brands_labels',
    ]);

host('brands-stage-1-2')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-labels-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'brands-stage',
        'theme' => 'brands_labels',
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
        'theme' => 'sololuxury',
    ]);

host('solo-prod-1-2')
    ->set('hostname', '173.249.14.153')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
        'theme' => 'sololuxury',
    ]);

host('solo-prod-2-1')
    ->set('hostname', '84.46.240.184')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
        'theme' => 'sololuxury',
    ]);

host('solo-prod-2-2')
    ->set('hostname', '84.46.240.184')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
        'theme' => 'sololuxury',
    ]);

host('solo-prod-3-1')
    ->set('hostname', '173.249.14.153')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-3-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
        'theme' => 'sololuxury',
    ]);

host('solo-prod-3-2')
    ->set('hostname', '173.249.14.153')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-3-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
        'theme' => 'sololuxury',
    ]);

host('solo-prod-4-1')
    ->set('hostname', '84.46.240.184')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-4-1')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
        'theme' => 'sololuxury',
    ]);

host('solo-prod-4-2')
    ->set('hostname', '84.46.240.184')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-4-2')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-prod',
        'theme' => 'sololuxury',
    ]);

host('solo-qa-1-1')
    ->set('hostname', '85.208.51.101')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/qa-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-qa',
        'theme' => 'sololuxury',
    ]);

host('solo-qa-1-2')
    ->set('hostname', '85.208.51.101')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/qa-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-qa',
        'theme' => 'sololuxury',
    ]);

host('solo-stage-1-1')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/sololuxury-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-stage',
        'theme' => 'sololuxury',
    ]);

host('solo-stage-1-2')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/sololuxury-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'solo-stage',
        'theme' => 'sololuxury',
    ]);


host('avoir-chic-prod-1-1')
    ->set('hostname', '184.174.32.145')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-prod',
        'theme' => 'avoirchic',
    ]);

host('avoir-chic-prod-1-2')
    ->set('hostname', '184.174.32.145')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-prod',
        'theme' => 'avoirchic',
    ]);

host('avoir-chic-prod-2-1')
    ->set('hostname', '184.174.32.149')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-prod',
        'theme' => 'avoirchic',
    ]);

host('avoir-chic-prod-2-2')
    ->set('hostname', '184.174.32.149')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-prod',
        'theme' => 'avoirchic',
    ]);


host('avoir-chic-prod-3-1')
    ->set('hostname', '184.174.32.145')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-3-1')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-prod',
        'theme' => 'avoirchic',
    ]);

host('avoir-chic-prod-3-2')
    ->set('hostname', '184.174.32.145')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-3-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-prod',
        'theme' => 'avoirchic',
    ]);

host('avoir-chic-prod-4-1')
    ->set('hostname', '184.174.32.149')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-4-1')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-prod',
        'theme' => 'avoirchic',
    ]);

host('avoir-chic-prod-4-2')
    ->set('hostname', '184.174.32.149')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-4-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-prod',
        'theme' => 'avoirchic',
    ]);

host('avoir-chic-stage-1-1')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home2/avoir-chic-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-stage',
        'theme' => 'avoirchic',
    ]);

host('avoir-chic-stage-1-2')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home2/avoir-chic-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'avoir-chic-stage',
        'theme' => 'avoirchic',
    ]);



host('veralusso-prod-1-1')
    ->set('hostname', '184.174.32.43')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-prod',
        'theme' => 'veralusso',
    ]);

host('veralusso-prod-1-2')
    ->set('hostname', '184.174.32.43')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-prod',
        'theme' => 'veralusso',
    ]);

host('veralusso-prod-3-1')
    ->set('hostname', '184.174.32.43')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-3-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-prod',
        'theme' => 'veralusso',
    ]);

host('veralusso-prod-3-2')
    ->set('hostname', '184.174.32.43')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-3-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-prod',
        'theme' => 'veralusso',
    ]);

host('veralusso-prod-2-1')
    ->set('hostname', '184.174.32.58')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-prod',
        'theme' => 'veralusso',
    ]);

host('veralusso-prod-2-2')
    ->set('hostname', '184.174.32.58')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-prod',
        'theme' => 'veralusso',
    ]);

host('veralusso-prod-4-1')
    ->set('hostname', '184.174.32.58')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-4-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-prod',
        'theme' => 'veralusso',
    ]);

host('veralusso-prod-4-2')
    ->set('hostname', '184.174.32.58')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-4-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-prod',
        'theme' => 'veralusso',
    ]);



host('veralusso-stage-1-1')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-stage',
        'theme' => 'veralusso',
    ]);

host('veralusso-stage-1-2')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'veralusso-stage',
        'theme' => 'veralusso',
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
        'theme' => 'suvandnat',
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
        'theme' => 'suvandnat',
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
        'theme' => 'suvandnat',
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
        'theme' => 'suvandnat',
    ]);


host('suvandnat-prod-3-1')
    ->set('hostname', '38.242.235.10')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-3-1')
    ->set('static_content_locales', 'en_US de_DE fr_FR ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'suvandnat-prod',
        'theme' => 'suvandnat',
    ]);
    
host('suvandnat-prod-3-2')
    ->set('hostname', '38.242.235.10')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-3-2')
    ->set('static_content_locales', 'en_US')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'suvandnat-prod',
        'theme' => 'suvandnat',
    ]);
 
 host('suvandnat-prod-4-1')
    ->set('hostname', '38.242.235.6')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-4-1')
    ->set('static_content_locales', 'en_US es_ES')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'suvandnat-prod',
        'theme' => 'suvandnat',
    ]);
    
 host('suvandnat-prod-4-2')
    ->set('hostname', '38.242.235.6')
    ->set('port', '22480')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/prod-4-2')
    ->set('static_content_locales', 'en_US es_ES')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'suvandnat-prod',
        'theme' => 'suvandnat',
    ]);

host('suvandnat-stage-1-1')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home2/suvandnat-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'suvandnat-stage',
        'theme' => 'suvandnat',
    ]);

host('suvandnat-stage-1-2')
    ->set('hostname', '185.190.140.72')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home2/suvandnat-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'suvandnat-stage',
        'theme' => 'suvandnat',
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
