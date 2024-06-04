<?php
return [
    'backend' => [
        'frontName' => 'brands-wadm-1-1'
    ],
    'install' => [
        'date' => 'Thu, 24 Sep 2020 16:56:17 +0000'
    ],
    'crypt' => [
        'key' => 'XYLm7obbymFUpDbs6ocUTLMcgWL9wcvT'
    ],
    'session' => [
        'save' => 'redis',
        'redis' => [
            'host' => '/var/run/redis/redis-server.sock',
            'port' => '6379',
            'password' => '',
            'timeout' => '2.5',
            'persistent_identifier' => '',
            'database' => '2',
            'compression_threshold' => '2048',
            'compression_library' => 'gzip',
            'log_level' => '4',
            'max_concurrency' => '6',
            'break_after_frontend' => '5',
            'break_after_adminhtml' => '30',
            'first_lifetime' => '600',
            'bot_first_lifetime' => '60',
            'bot_lifetime' => '7200',
            'disable_locking' => '0',
            'min_lifetime' => '60',
            'max_lifetime' => '2592000',
            'sentinel_master' => '',
            'sentinel_servers' => '',
            'sentinel_connect_retries' => '5',
            'sentinel_verify_master' => '0'
        ]
    ],
    'cache' => [
        'frontend' => [
            'default' => [
                'id_prefix' => 'e1e_',
                'backend' => 'Magento\\Framework\\Cache\\Backend\\Redis',
                'backend_options' => [
                    'server' => '/var/run/redis/redis-server.sock',
                    'database' => '0',
                    'port' => '6379',
                    'password' => '',
                    'compress_data' => '1',
                    'compression_lib' => ''
                ]
            ],
            'page_cache' => [
                'id_prefix' => 'e1e_',
                'backend' => 'Magento\\Framework\\Cache\\Backend\\Redis',
                'backend_options' => [
                    'server' => '/var/run/redis/redis-server.sock',
                    'database' => '1',
                    'port' => '6379',
                    'password' => '',
                    'compress_data' => '0',
                    'compression_lib' => ''
                ]
            ]
        ],
        'allow_parallel_generation' => false,
        'graphql' => [
            'id_salt' => 'jdXkKaVj2EbGxQKsVHuLfdI1qTMNb7kS'
        ]
    ],
    'db' => [
        'table_prefix' => '',
        'connection' => [
            'default' => [
                'host' => 'db',
                'dbname' => 'magento2',
                'username' => 'root',
                'password' => 'root',
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;',
                'active' => '1',
                'driver_options' => [
                    1014 => false
                ]
            ]
        ]
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'SAMEORIGIN',
    'MAGE_MODE' => 'developer',
    'cache_types' => [
        'config' => 1,
        'layout' => 1,
        'block_html' => 1,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 1,
        'eav' => 1,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'full_page' => 1,
        'translate' => 1,
        'config_webservice' => 1,
        'compiled_config' => 1,
        'customer_notification' => 1,
        'google_product' => 1,
        'vertex' => 1,
        'amasty_shopby' => 1
    ],
    'system' => [
        'default' => [
            'payment' => [
                'payflowpro' => [
                    'partner' => null,
                    'user' => null,
                    'pwd' => null,
                    'sandbox_flag' => '0',
                    'proxy_host' => null,
                    'proxy_port' => null,
                    'debug' => '0'
                ],
                'payflow_link' => [
                    'pwd' => null,
                    'sandbox_flag' => '0',
                    'use_proxy' => '0',
                    'proxy_host' => null,
                    'proxy_port' => null,
                    'debug' => '0',
                    'url_method' => 'GET'
                ],
                'payflow_express' => [
                    'debug' => '0'
                ],
                'paypal_express_bml' => [
                    'publisher_id' => null
                ],
                'paypal_express' => [
                    'debug' => '0',
                    'merchant_id' => null
                ],
                'hosted_pro' => [
                    'debug' => null
                ],
                'paypal_billing_agreement' => [
                    'debug' => '0'
                ],
                'adyen_abstract' => [
                    'demo_mode' => '1',
                    'api_key_test' => '0:3:OtCXQRkaQncJtQ2wH7p4Iky8nSBiI8QVtBEfoFvPo4Byh3l+FENd4kwprBJlt/cO0m8N2+dI1oxi6xQPwFZs0GqHOHQ6ZL4FZfLB0blmKZggERTCij7haF6CQZx4NZeLjUV7urmt7kri9StBw7hBQJgW8t8vgeXGSNy8ngll0X+Ccaz+aauWQCyO7jlz5uhw6whQFc2aqwzH88nxzIkJwiZDt1dqsYNrZSzv9ln37rfsoQ==',
                    'api_key_live' => null,
                    'client_key_test' => 'test_46JGYXUQUFHV5MIYZWBXPRW6XALSD6JJ',
                    'client_key_live' => null,
                    'merchant_account' => 'MioModaFzeECOM',
                    'live_endpoint_url_prefix' => null,
                    'notification_username' => null,
                    'notification_password' => '0:3:LkpbBDxkhKoDidD5UZmJDx5ju0UFTrBkt6Q+HA0pgv294WN0sANAXQ==',
                    'notification_hmac_key_test' => null,
                    'notification_hmac_key_live' => null,
                    'checkout_frontend_region' => null,
                    'debug' => '1'
                ],
                'adyen_pos_cloud' => [
                    'pos_merchant_account' => null,
                    'pos_store_id' => null,
                    'api_key_test' => null,
                    'api_key_live' => null
                ],
                'braintree' => [
                    'merchant_id' => null,
                    'public_key' => null,
                    'private_key' => null,
                    'merchant_account_id' => null
                ],
                'braintree_paypal' => [
                    'merchant_name_override' => null
                ],
                'checkmo' => [
                    'mailing_address' => null
                ],
                'payflow_advanced' => [
                    'user' => 'PayPal',
                    'pwd' => null,
                    'sandbox_flag' => '0',
                    'proxy_host' => null,
                    'proxy_port' => null,
                    'debug' => '0',
                    'url_method' => 'GET'
                ]
            ],
            'payment_all_paypal' => [
                'paypal_payflowpro' => [
                    'settings_paypal_payflow' => [
                        'heading_cc' => null,
                        'settings_paypal_payflow_advanced' => [
                            'paypal_payflow_settlement_report' => [
                                'heading_sftp' => null
                            ]
                        ]
                    ]
                ],
                'payflow_link' => [
                    'settings_payflow_link' => [
                        'settings_payflow_link_advanced' => [
                            'payflow_link_settlement_report' => [
                                'heading_sftp' => null
                            ]
                        ]
                    ]
                ],
                'payments_pro_hosted_solution' => [
                    'pphs_settings' => [
                        'pphs_settings_advanced' => [
                            'pphs_settlement_report' => [
                                'heading_sftp' => null
                            ]
                        ]
                    ]
                ],
                'express_checkout' => [
                    'settings_ec' => [
                        'settings_ec_advanced' => [
                            'express_checkout_settlement_report' => [
                                'heading_sftp' => null
                            ]
                        ]
                    ]
                ]
            ],
            'paypal' => [
                'fetch_reports' => [
                    'ftp_login' => null,
                    'ftp_password' => null,
                    'ftp_sandbox' => '0',
                    'ftp_ip' => null,
                    'ftp_path' => null
                ],
                'general' => [
                    'business_account' => null,
                    'merchant_country' => null
                ],
                'wpp' => [
                    'api_username' => null,
                    'api_password' => null,
                    'api_signature' => null,
                    'api_cert' => null,
                    'sandbox_flag' => '0',
                    'proxy_host' => null,
                    'proxy_port' => null
                ]
            ],
            'admin' => [
                'url' => [
                    'custom' => null,
                    'custom_path' => null
                ]
            ],
            'web' => [
                'unsecure' => [
                    'base_url' => 'http://localhost:81/',
                    'base_link_url' => '{{unsecure_base_url}}',
                    'base_static_url' => 'http://localhost:81/static/',
                    'base_media_url' => 'http://localhost:81/media/'
                ],
                'secure' => [
                    'base_url' => 'http://localhost:81/',
                    'base_link_url' => '{{secure_base_url}}',
                    'base_static_url' => 'http://localhost:81/static/',
                    'base_media_url' => 'http://localhost:81/media/'
                ],
                'default' => [
                    'front' => 'cms'
                ],
                'cookie' => [
                    'cookie_path' => null,
                    'cookie_domain' => null
                ]
            ],
            'catalog' => [
                'productalert_cron' => [
                    'error_email' => null
                ],
                'product_video' => [
                    'youtube_api_key' => null
                ],
                'search' => [
                    'engine' => 'elasticsuite',
                    'min_query_length' => '3',
                    'max_query_length' => '128',
                    'max_count_cacheable_search_terms' => '100',
                    'autocomplete_limit' => '8',
                    'enable_eav_indexer' => '1',
                    'search_suggestion_enabled' => '1',
                    'search_suggestion_count' => '2',
                    'search_suggestion_count_results_enabled' => '0',
                    'search_recommendations_enabled' => '1',
                    'search_recommendations_count' => '5',
                    'search_recommendations_count_results_enabled' => '0',
                    'elasticsearch6_server_hostname' => 'localhost',
                    'elasticsearch6_server_port' => '9200',
                    'elasticsearch6_index_prefix' => 'magento2',
                    'elasticsearch6_enable_auth' => '0',
                    'elasticsearch6_server_timeout' => '15'
                ]
            ],
            'cataloginventory' => [
                'source_selection_distance_based_google' => [
                    'api_key' => null
                ]
            ],
            'currency' => [
                'import' => [
                    'error_email' => null
                ]
            ],
            'sitemap' => [
                'generate' => [
                    'error_email' => null
                ]
            ],
            'trans_email' => [
                'ident_general' => [
                    'name' => 'Admin',
                    'email' => 'care@brands-labels.com'
                ],
                'ident_sales' => [
                    'name' => 'Sales',
                    'email' => 'care@brands-labels.com'
                ],
                'ident_support' => [
                    'name' => 'Care',
                    'email' => 'care@brands-labels.com'
                ],
                'ident_custom1' => [
                    'name' => 'Info',
                    'email' => 'care@brands-labels.com'
                ],
                'ident_custom2' => [
                    'name' => 'No-Reply',
                    'email' => 'care@brands-labels.com'
                ]
            ],
            'contact' => [
                'email' => [
                    'recipient_email' => 'hello@example.com'
                ]
            ],
            'sales_email' => [
                'order' => [
                    'copy_to' => null
                ],
                'order_comment' => [
                    'copy_to' => null
                ],
                'invoice' => [
                    'copy_to' => null
                ],
                'invoice_comment' => [
                    'copy_to' => null
                ],
                'shipment' => [
                    'copy_to' => null
                ],
                'shipment_comment' => [
                    'copy_to' => null
                ],
                'creditmemo' => [
                    'copy_to' => null
                ],
                'creditmemo_comment' => [
                    'copy_to' => null
                ]
            ],
            'checkout' => [
                'payment_failed' => [
                    'copy_to' => null
                ]
            ],
            'carriers' => [
                'ups' => [
                    'is_account_live' => '0',
                    'access_license_number' => null,
                    'gateway_xml_url' => 'https://onlinetools.ups.com/ups.app/xml/Rate',
                    'password' => null,
                    'username' => null,
                    'gateway_url' => 'https://www.ups.com/using/services/rave/qcostcgi.cgi',
                    'shipper_number' => null,
                    'tracking_xml_url' => 'https://onlinetools.ups.com/ups.app/xml/Track',
                    'debug' => '0'
                ],
                'usps' => [
                    'gateway_url' => 'http://production.shippingapis.com/ShippingAPI.dll',
                    'gateway_secure_url' => 'https://secure.shippingapis.com/ShippingAPI.dll',
                    'userid' => null,
                    'password' => null
                ],
                'fedex' => [
                    'account' => null,
                    'meter_number' => null,
                    'key' => null,
                    'password' => null,
                    'sandbox_mode' => '0',
                    'production_webservices_url' => 'https://ws.fedex.com:443/web-services/',
                    'sandbox_webservices_url' => 'https://wsbeta.fedex.com:443/web-services/',
                    'smartpost_hubid' => null
                ],
                'dhl' => [
                    'id' => null,
                    'password' => null,
                    'account' => null,
                    'debug' => '0',
                    'gateway_url' => 'https://xmlpi-ea.dhl.com/XMLShippingServlet'
                ]
            ],
            'google' => [
                'analytics' => [
                    'account' => 'G-SGY4Y0R4JR'
                ],
                'gtag' => [
                    'analytics4' => [
                        'measurement_id' => null
                    ],
                    'adwords' => [
                        'conversion_id' => null
                    ]
                ]
            ],
            'recaptcha_backend' => [
                'type_recaptcha' => [
                    'public_key' => null,
                    'private_key' => null
                ],
                'type_invisible' => [
                    'public_key' => null,
                    'private_key' => null
                ],
                'type_recaptcha_v3' => [
                    'public_key' => null,
                    'private_key' => null
                ]
            ],
            'recaptcha_frontend' => [
                'type_recaptcha' => [
                    'public_key' => null,
                    'private_key' => null
                ],
                'type_invisible' => [
                    'public_key' => null,
                    'private_key' => null
                ],
                'type_recaptcha_v3' => [
                    'public_key' => null,
                    'private_key' => null
                ]
            ],
            'system' => [
                'smtp' => [
                    'host' => 'localhost',
                    'port' => '25'
                ],
                'gmailsmtpapp' => [
                    'active' => '0',
                    'auth' => 'LOGIN',
                    'ssl' => 'ssl',
                    'smtphost' => 'smtp.gmail.com',
                    'smtpport' => '465',
                    'username' => 'brandsandlabelsdubai@gmail.com',
                    'password' => '0:3:tcdd1hR2q0rMtnMM4d0s+b8mb7cBnPjZdr/H8pIU1ksVACYdX0Lgk6oqrONRjQCY',
                    'set_reply_to' => '1',
                    'set_from' => '0',
                    'custom_from_email' => null,
                    'return_path_email' => null
                ],
                'full_page_cache' => [
                    'varnish' => [
                        'access_list' => 'localhost',
                        'backend_host' => 'localhost',
                        'backend_port' => '8080'
                    ]
                ],
                'release_notification' => [
                    'content_url' => 'magento.com/release_notifications',
                    'use_https' => '1'
                ]
            ],
            'adobe_ims' => [
                'integration' => [
                    'api_key' => null,
                    'private_key' => null
                ]
            ],
            'dev' => [
                'restrict' => [
                    'allow_ips' => '103.79.169.130'
                ],
                'js' => [
                    'session_storage_key' => 'collected_errors'
                ]
            ],
            'newrelicreporting' => [
                'general' => [
                    'api_url' => 'https://api.newrelic.com/deployments.xml',
                    'insights_api_url' => 'https://insights-collector.newrelic.com/v1/accounts/%s/events',
                    'account_id' => '3537655',
                    'app_id' => '1489738014',
                    'api' => '0:3:XY5X8cTFGdvdP86nC3+Nqec1xwI+dwwfhpg/oJtd5nFubo3BYH7pCszo4tQ9AHdf9KtOx+tyHgBmC1NA',
                    'insights_insert_key' => '0:3:I811ksE6+DDebvc/E4NAMXwSUZLxdg6gzOUA5D9QXsp2hjzqq+nV/6ba6ETkanf7tRr4RJ5WA7VRNv3ou+zf7Yc='
                ]
            ],
            'analytics' => [
                'general' => [
                    'token' => null
                ],
                'url' => [
                    'signup' => 'https://advancedreporting.rjmetrics.com/signup',
                    'update' => 'https://advancedreporting.rjmetrics.com/update',
                    'bi_essentials' => 'https://dashboard.rjmetrics.com/v2/magento/signup',
                    'otp' => 'https://advancedreporting.rjmetrics.com/otp',
                    'report' => 'https://advancedreporting.rjmetrics.com/report',
                    'notify_data_changed' => 'https://advancedreporting.rjmetrics.com/report'
                ]
            ],
            'crontab' => [
                'default' => [
                    'jobs' => [
                        'analytics_collect_data' => [
                            'schedule' => [
                                'cron_expr' => '00 02 * * *'
                            ]
                        ],
                        'analytics_subscribe' => [
                            'schedule' => [
                                'cron_expr' => '0 * * * *'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'lock' => [
        'provider' => 'db'
    ],
    'remote_storage' => [
        'driver' => 'file'
    ],
    'queue' => [
        'consumers_wait_for_messages' => 1
    ],
    'directories' => [
        'document_root_is_pub' => true
    ]
];
