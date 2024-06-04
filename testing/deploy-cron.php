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

    if ($PROJECT_NAME == "brands-prod")
    {
            writeln(get('labels')['env']);
            writeln(get('labels')['proj']);
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
    run("cd {{release_or_current_path}} && bin/magento setup:static-content:deploy -f --jobs=$(nproc) -n");
});

task('deploy:magento-cache-flush', function() {
    run("cd {{release_or_current_path}} && bin/magento cache:flush");
});


//after('deploy:clear_paths', 'deploy:magento-composer-install');
//after('deploy:magento-composer-install', 'deploy:copyconfig');
after('deploy:clear_paths', 'deploy:copyconfig');
after('deploy:copyconfig', 'deploy:magento-setup-upgrade');
after('deploy:magento-setup-upgrade', 'deploy:magento-compile');
//after('deploy:magento-compile', 'deploy:magento-autoload');
//after('deploy:magento-autoload', 'deploy:magento-content-deploy');
//after('deploy:magento-content-deploy', 'deploy:indexes');
//after('deploy:indexes', 'deploy:magento-cache-flush');
//after('magento:cache:flush', 'deploy:fpm:restart');
//after('deploy:fpm:restart', 'deploy:varnish:restart');

// Disabled Tasks

task('magento:compile')->disable();
task('magento:sync:content_version')->disable();
task('magento:deploy:assets')->disable();
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
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-prod-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'brands-prod',
    ]);

host('brands-prod-1-2')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'brands-prod',
    ]);

host('brands-prod-2-1')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'brands-prod',
    ]);

host('brands-prod-2-2')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'brands-prod',
    ]);

host('brands-prod-3-1')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-prod-3-1')
    ->set('static_content_locales', 'en_US de_DE fr_FR ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'brands-prod',
    ]);

host('brands-prod-3-2')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-prod-3-2')
    ->set('static_content_locales', 'en_US')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'brands-prod',
    ]);

host('brands-prod-4-1')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-prod-4-1')
    ->set('static_content_locales', 'en_US es_ES')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'brands-prod',
    ]);

host('brands-prod-4-2')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/brands-prod-4-2')
    ->set('static_content_locales', 'en_US es_ES')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'brands-prod',
    ]);

// Hosts Sololuxury

host('solo-prod-1-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/solo-prod-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'solo-prod',
    ]);

host('solo-prod-1-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/solo-prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'solo-prod',
    ]);

host('solo-prod-2-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/solo-prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'solo-prod',
    ]);

host('solo-prod-2-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/solo-prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'solo-prod',
    ]);

host('solo-prod-3-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/solo-prod-3-1')
    ->set('static_content_locales', 'en_US de_DE fr_FR ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'solo-prod',
    ]);

host('solo-prod-3-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/solo-prod-3-2')
    ->set('static_content_locales', 'en_US')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'solo-prod',
    ]);

host('solo-prod-4-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/solo-prod-4-1')
    ->set('static_content_locales', 'en_US es_ES')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'solo-prod',
    ]);

host('solo-prod-4-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/solo-prod-4-2')
    ->set('static_content_locales', 'en_US es_ES')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'solo-prod',
    ]);

host('avoir-chic-prod-1-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/avoir-chic-prod-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'avoir-chic-prod',
    ]);

host('avoir-chic-prod-1-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/avoir-chic-prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'avoir-chic-prod',
    ]);

host('avoir-chic-prod-2-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/avoir-chic-prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'avoir-chic-prod',
    ]);

host('avoir-chic-prod-2-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/avoir-chic-prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'avoir-chic-prod',
    ]);

host('avoir-chic-prod-3-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/avoir-chic-prod-3-1')
    ->set('static_content_locales', 'en_US de_DE fr_FR ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'avoir-chic-prod',
    ]);

host('avoir-chic-prod-3-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/avoir-chic-prod-3-2')
    ->set('static_content_locales', 'en_US')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'avoir-chic-prod',
    ]);

host('avoir-chic-prod-4-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/avoir-chic-prod-4-1')
    ->set('static_content_locales', 'en_US es_ES')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'avoir-chic-prod',
    ]);

host('avoir-chic-prod-4-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/avoir-chic-prod-4-2')
    ->set('static_content_locales', 'en_US es_ES')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'avoir-chic-prod',
    ]);

host('veralusso-prod-1-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-prod-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'veralusso-prod',
    ]);

host('veralusso-prod-1-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'veralusso-prod',
    ]);

host('veralusso-prod-2-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'veralusso-prod',
    ]);

host('veralusso-prod-2-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'veralusso-prod',
    ]);

host('veralusso-prod-3-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-prod-3-1')
    ->set('static_content_locales', 'en_US de_DE fr_FR ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'veralusso-prod',
    ]);

host('veralusso-prod-3-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-prod-3-2')
    ->set('static_content_locales', 'en_US')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'veralusso-prod',
    ]);

host('veralusso-prod-4-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-prod-4-1')
    ->set('static_content_locales', 'en_US es_ES')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'veralusso-prod',
    ]);

host('veralusso-prod-4-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/veralusso-prod-4-2')
    ->set('static_content_locales', 'en_US es_ES')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'veralusso-prod',
    ]);

host('suvandnat-prod-1-1')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/suvandnat-prod-1-1')
    ->set('static_content_locales', 'en_US ar_SA')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'suvandnat-prod',
    ]);

host('suvandnat-prod-1-2')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('port', '22')
    ->set('deploy_path', '/home/suvandnat-prod-1-2')
    ->set('static_content_locales', 'en_US ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'suvandnat-prod',
    ]);

host('suvandnat-prod-2-1')
    ->set('hostname', '139.99.8.45')
    ->set('remote_user', 'root')
    ->set('port', '22')
    ->set('deploy_path', '/home/suvandnat-prod-2-1')
    ->set('static_content_locales', 'en_US es_ES fr_FR ja_JP ko_KR zh_Hans_CN')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'suvandnat-prod',
    ]);

host('suvandnat-prod-2-2')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/suvandnat-prod-2-2')
    ->set('static_content_locales', 'en_US es_ES it_IT')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'suvandnat-prod',
    ]);

host('suvandnat-prod-3-1')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/suvandnat-prod-3-1')
    ->set('static_content_locales', 'en_US de_DE fr_FR ru_RU')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'suvandnat-prod',
    ]);

host('suvandnat-prod-3-2')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/suvandnat-prod-3-2')
    ->set('static_content_locales', 'en_US')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'suvandnat-prod',
    ]);

host('suvandnat-prod-4-1')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/suvandnat-prod-4-1')
    ->set('static_content_locales', 'en_US es_ES')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'suvandnat-prod',
    ]);

host('suvandnat-prod-4-2')
    ->set('hostname', '139.99.8.45')
    ->set('port', '22')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/home/suvandnat-prod-4-2')
    ->set('static_content_locales', 'en_US es_ES')
    ->set('default_timeout', '5000')
    ->setLabels([
        'env' => 'cronjobs',
        'proj' => 'suvandnat-prod',
    ]);


after('deploy:failed', 'deploy:unlock');

desc('Fix elasticsearch memory settings');
task('fix:elasticsearch', function () {
    run("sed -i 's/Xms1g/Xms4g/' /etc/elasticsearch/jvm.options");
    run("sed -i 's/Xmx1g/Xmx4g/' /etc/elasticsearch/jvm.options");
    run("cd /usr/share/elasticsearch && bin/elasticsearch-plugin install analysis-phonetic");
    run("cd /usr/share/elasticsearch && bin/elasticsearch-plugin install analysis-icu");
    run('service elasticsearch restart');
})->oncePerNode();

