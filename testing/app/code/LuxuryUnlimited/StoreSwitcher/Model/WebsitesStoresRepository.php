<?php

namespace LuxuryUnlimited\StoreSwitcher\Model;

use Dynamic\Customization\Model\WebsitesStoresRepositoryAbstract;

class WebsitesStoresRepository extends WebsitesStoresRepositoryAbstract
{
    /**
     * Get list of websites
     *
     * @SuppressWarnings(ExcessiveMethodLength)
     *
     * @return array
     */
    public function getList()
    {
        
        $websites = $this->getWebsites();
        $data = [];
        foreach ($websites as $website) {
            $data[] = $this->websiteFactory->create()->setData($website);
        }

        return $data;
    }

    /**
     * *
     *
     * @SuppressWarnings("PMD.AllPurposeAction")
     * @SuppressWarnings("PMD.ExcessiveMethodLength")
     */
    public function getWebsites()
    {
        $websites = [
            [
                'website_id' => 15,
                'name' => 'Albania',
                'code' => 'al',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'al-en',
                        'name' => 'English',
                    ],
                ],
            ],
            [
                'website_id' => 16,
                'name' => 'Argentina',
                'code' => 'ar',
                'default_display_currency_code' => 'ARS',
                'store_list' => [
                    [
                        'code' => 'ar-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'ar-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 17,
                'name' => 'Armenia',
                'code' => 'am',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'am-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 18,
                'name' => 'Aruba',
                'code' => 'aw',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'aw-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 19,
                'name' => 'Australia',
                'code' => 'au',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'au-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 20,
                'name' => 'Austria',
                'code' => 'at',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'at-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'at-ge',
                        'name' => 'German',
                    ],
                ],
            ],
            [
                'website_id' => 21,
                'name' => 'Bahamas, The',
                'code' => 'bs',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'bs-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 22,
                'name' => 'Bahrain',
                'code' => 'bh',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'bh-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'bh-ar',
                        'name' => 'Arabic',
                    ],
                ],
            ],
            [
                'website_id' => 23,
                'name' => 'Barbados',
                'code' => 'bb',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'bb-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 24,
                'name' => 'Belarus',
                'code' => 'by',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'by-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 25,
                'name' => 'Belgium',
                'code' => 'be',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'be-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'be-fr',
                        'name' => 'French',
                    ],
                ],
            ],
            [
                'website_id' => 26,
                'name' => 'Bolivia',
                'code' => 'bo',
                'default_display_currency_code' => 'BOB',
                'store_list' => [
                    [
                        'code' => 'bo-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'bo-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 27,
                'name' => 'Botswana',
                'code' => 'bw',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'bw-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 28,
                'name' => 'Brazil',
                'code' => 'br',
                'default_display_currency_code' => 'BRL',
                'store_list' => [
                    [
                        'code' => 'br-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'br-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 29,
                'name' => 'Bulgaria',
                'code' => 'bg',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'bg-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 30,
                'name' => 'Canada',
                'code' => 'ca',
                'default_display_currency_code' => 'CAD',
                'store_list' => [
                    [
                        'code' => 'ca-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'ca-fr',
                        'name' => 'French',
                    ],
                ],
            ],
            [
                'website_id' => 31,
                'name' => 'Chile',
                'code' => 'cl',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'cl-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 32,
                'name' => 'China',
                'code' => 'cn',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'cn-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'cn-cn',
                        'name' => 'Chinese',
                    ],
                ],
            ],
            [
                'website_id' => 33,
                'name' => 'Chinese Taipei',
                'code' => 'tw',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'tw-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 34,
                'name' => 'Colombia',
                'code' => 'co',
                'default_display_currency_code' => 'COP',
                'store_list' => [
                    [
                        'code' => 'co-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'co-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 35,
                'name' => 'Costa Rica',
                'code' => 'cr',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'cr-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 36,
                'name' => 'Croatia',
                'code' => 'hr',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'hr-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 37,
                'name' => 'Cyprus',
                'code' => 'cy',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'cy-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 38,
                'name' => 'Czech Republic',
                'code' => 'cz',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'cz-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'cz-ru',
                        'name' => 'Russian',
                    ],
                ],
            ],
            [
                'website_id' => 39,
                'name' => 'Denmark',
                'code' => 'dk',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'dk-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 40,
                'name' => 'Ecuador',
                'code' => 'ec',
                'default_display_currency_code' => 'USD',
                'store_list' => [
                    [
                        'code' => 'ec-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'ec-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 41,
                'name' => 'Egypt',
                'code' => 'eg',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'eg-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'eg-ar',
                        'name' => 'Arabic',
                    ],
                ],
            ],
            [
                'website_id' => 42,
                'name' => 'El Salvador',
                'code' => 'sv',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'sv-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'sv-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 43,
                'name' => 'Estonia',
                'code' => 'ee',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'ee-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 44,
                'name' => 'Finland',
                'code' => 'fi',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'fi-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 45,
                'name' => 'France',
                'code' => 'fr',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'fr-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'fr-fr',
                        'name' => 'French',
                    ],
                ],
            ],
            [
                'website_id' => 46,
                'name' => 'Georgia',
                'code' => 'ge',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'ge-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 47,
                'name' => 'Germany',
                'code' => 'de',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'de-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'de-ge',
                        'name' => 'German',
                    ],
                ],
            ],
            [
                'website_id' => 48,
                'name' => 'Ghana',
                'code' => 'gh',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'gh-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 49,
                'name' => 'Greece',
                'code' => 'gr',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'gr-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 50,
                'name' => 'Guatemala',
                'code' => 'gt',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'gt-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'gt-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 51,
                'name' => 'Honduras',
                'code' => 'hn',
                'default_display_currency_code' => 'HNL',
                'store_list' => [
                    [
                        'code' => 'hn-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'hn-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 52,
                'name' => 'Hong Kong',
                'code' => 'hk',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'hk-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'hk-cn',
                        'name' => 'Chinese',
                    ],
                ],
            ],
            [
                'website_id' => 53,
                'name' => 'Hungary',
                'code' => 'hu',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'hu-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 54,
                'name' => 'Iceland',
                'code' => 'is',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'is-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 50,
                'name' => 'India',
                'code' => 'in',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'in-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 56,
                'name' => 'Indonesia',
                'code' => 'id',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'id-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 57,
                'name' => 'Ireland',
                'code' => 'ie',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'ie-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 58,
                'name' => 'Israel',
                'code' => 'il',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'il-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 59,
                'name' => 'Italy',
                'code' => 'it',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'it-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'it-it',
                        'name' => 'Italian',
                    ],
                ],
            ],
            [
                'website_id' => 60,
                'name' => 'Japan',
                'code' => 'jp',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'jp-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'jp-jp',
                        'name' => 'Japanese',
                    ],
                ],
            ],
            [
                'website_id' => 61,
                'name' => 'Jordan',
                'code' => 'jo',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'jo-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'jo-ar',
                        'name' => 'Arabic',
                    ],
                ],
            ],
            [
                'website_id' => 62,
                'name' => 'Kazakhstan',
                'code' => 'kz',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'kz-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 63,
                'name' => 'Kenya',
                'code' => 'ke',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'ke-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 64,
                'name' => 'Kuwait',
                'code' => 'kw',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'kw-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'kw-ar',
                        'name' => 'Arabic',
                    ],
                ],
            ],
            [
                'website_id' => 65,
                'name' => 'Latvia',
                'code' => 'lv',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'lv-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 66,
                'name' => 'Lithuania',
                'code' => 'lt',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'lt-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 67,
                'name' => 'Luxembourg',
                'code' => 'lu',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'lu-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 68,
                'name' => 'Madagascar',
                'code' => 'mg',
                'default_display_currency_code' => 'MGA',
                'store_list' => [
                    [
                        'code' => 'mg-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 69,
                'name' => 'Malaysia',
                'code' => 'my',
                'default_display_currency_code' => 'MYR',
                'store_list' => [
                    [
                        'code' => 'my-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 70,
                'name' => 'Maldives',
                'code' => 'mv',
                'default_display_currency_code' => 'MVR',
                'store_list' => [
                    [
                        'code' => 'mv-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 71,
                'name' => 'Malta',
                'code' => 'mt',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'mt-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 72,
                'name' => 'Mauritius',
                'code' => 'mu',
                'default_display_currency_code' => 'MUR',
                'store_list' => [
                    [
                        'code' => 'mu-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 73,
                'name' => 'Mexico',
                'code' => 'mx',
                'default_display_currency_code' => 'MXN',
                'store_list' => [
                    [
                        'code' => 'mx-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'mx-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 74,
                'name' => 'Moldova',
                'code' => 'md',
                'default_display_currency_code' => 'MDL',
                'store_list' => [
                    [
                        'code' => 'md-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 75,
                'name' => 'Mongolia',
                'code' => 'mn',
                'default_display_currency_code' => 'MNT',
                'store_list' => [
                    [
                        'code' => 'mn-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 76,
                'name' => 'Morocco',
                'code' => 'ma',
                'default_display_currency_code' => 'MAD',
                'store_list' => [
                    [
                        'code' => 'ma-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 77,
                'name' => 'Namibia',
                'code' => 'na',
                'default_display_currency_code' => 'NAD',
                'store_list' => [
                    [
                        'code' => 'na-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 78,
                'name' => 'Netherlands',
                'code' => 'nl',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'nl-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 79,
                'name' => 'New Zealand',
                'code' => 'nz',
                'default_display_currency_code' => 'NZD',
                'store_list' => [
                    [
                        'code' => 'nz-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 80,
                'name' => 'Nicaragua',
                'code' => 'ni',
                'default_display_currency_code' => 'NIC',
                'store_list' => [
                    [
                        'code' => 'ni-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 81,
                'name' => 'Nigeria',
                'code' => 'ng',
                'default_display_currency_code' => 'NGN',
                'store_list' => [
                    [
                        'code' => 'ng-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 82,
                'name' => 'Norway',
                'code' => 'no',
                'default_display_currency_code' => 'NOK',
                'store_list' => [
                    [
                        'code' => 'no-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 83,
                'name' => 'Oman',
                'code' => 'om',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'om-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'om-ar',
                        'name' => 'Arabic',
                    ],
                ],
            ],
            [
                'website_id' => 84,
                'name' => 'Pakistan',
                'code' => 'pk',
                'default_display_currency_code' => 'PKR',
                'store_list' => [
                    [
                        'code' => 'pk-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 85,
                'name' => 'Panama',
                'code' => 'pa',
                'default_display_currency_code' => 'USD',
                'store_list' => [
                    [
                        'code' => 'pa-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'pa-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 86,
                'name' => 'Papua New Guinea',
                'code' => 'pg',
                'default_display_currency_code' => 'PGK',
                'store_list' => [
                    [
                        'code' => 'pg-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 87,
                'name' => 'Paraguay',
                'code' => 'py',
                'default_display_currency_code' => 'PYG',
                'store_list' => [
                    [
                        'code' => 'py-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 88,
                'name' => 'Peru',
                'code' => 'pe',
                'default_display_currency_code' => 'PEN',
                'store_list' => [
                    [
                        'code' => 'pe-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 89,
                'name' => 'Philippines',
                'code' => 'ph',
                'default_display_currency_code' => 'PHP',
                'store_list' => [
                    [
                        'code' => 'ph-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 90,
                'name' => 'Poland',
                'code' => 'pl',
                'default_display_currency_code' => 'PLN',
                'store_list' => [
                    [
                        'code' => 'pl-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 91,
                'name' => 'Portugal',
                'code' => 'pt',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'pt-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 92,
                'name' => 'Qatar',
                'code' => 'qa',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'qa-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'qa-ar',
                        'name' => 'Arabic',
                    ],
                ],
            ],
            [
                'website_id' => 93,
                'name' => 'Romania',
                'code' => 'ro',
                'default_display_currency_code' => 'RON',
                'store_list' => [
                    [
                        'code' => 'ro-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 94,
                'name' => 'Russia',
                'code' => 'ru',
                'default_display_currency_code' => 'RUB',
                'store_list' => [
                    [
                        'code' => 'ru-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'ru-ru',
                        'name' => 'Russian',
                    ],
                ],
            ],
            [
                'website_id' => 95,
                'name' => 'Rwanda',
                'code' => 'rw',
                'default_display_currency_code' => 'RWF',
                'store_list' => [
                    [
                        'code' => 'rw-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 96,
                'name' => 'Saudi Arabia',
                'code' => 'sa',
                'default_display_currency_code' => 'SAR',
                'store_list' => [
                    [
                        'code' => 'sa-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 97,
                'name' => 'Seychelles',
                'code' => 'sc',
                'default_display_currency_code' => 'SCR',
                'store_list' => [
                    [
                        'code' => 'sc-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 98,
                'name' => 'Singapore',
                'code' => 'sg',
                'default_display_currency_code' => 'SGD',
                'store_list' => [
                    [
                        'code' => 'sg-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 99,
                'name' => 'Slovakia',
                'code' => 'sk',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'sk-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 100,
                'name' => 'Slovenia',
                'code' => 'si',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'si-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 101,
                'name' => 'South Africa',
                'code' => 'za',
                'default_display_currency_code' => 'ZAR',
                'store_list' => [
                    [
                        'code' => 'za-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 102,
                'name' => 'South Korea',
                'code' => 'kr',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'kr-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'kr-kr',
                        'name' => 'Korean',
                    ],
                ],
            ],
            [
                'website_id' => 103,
                'name' => 'Spain',
                'code' => 'es',
                'default_display_currency_code' => 'EUR',
                'store_list' => [
                    [
                        'code' => 'es-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'es-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 104,
                'name' => 'Sweden',
                'code' => 'se',
                'default_display_currency_code' => 'SEK',
                'store_list' => [
                    [
                        'code' => 'se-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 106,
                'name' => 'Switzerland',
                'code' => 'ch',
                'default_display_currency_code' => 'CHF',
                'store_list' => [
                    [
                        'code' => 'ch-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 107,
                'name' => 'Tanzania',
                'code' => 'tz',
                'default_display_currency_code' => 'TZS',
                'store_list' => [
                    [
                        'code' => 'tz-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 108,
                'name' => 'Thailand',
                'code' => 'th',
                'default_display_currency_code' => 'THB',
                'store_list' => [
                    [
                        'code' => 'th-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 109,
                'name' => 'Trinidad and Tobago',
                'code' => 'tt',
                'default_display_currency_code' => 'TTD',
                'store_list' => [
                    [
                        'code' => 'tt-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 110,
                'name' => 'Tunisia',
                'code' => 'tn',
                'default_display_currency_code' => 'TND',
                'store_list' => [
                    [
                        'code' => 'tn-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 111,
                'name' => 'Turkey',
                'code' => 'tr',
                'default_display_currency_code' => 'TRY',
                'store_list' => [
                    [
                        'code' => 'tr-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 112,
                'name' => 'Uganda',
                'code' => 'ug',
                'default_display_currency_code' => 'UGX',
                'store_list' => [
                    [
                        'code' => 'ug-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 113,
                'name' => 'Ukraine',
                'code' => 'ua',
                'default_display_currency_code' => 'UAH',
                'store_list' => [
                    [
                        'code' => 'ua-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'ua-ru',
                        'name' => 'Russian',
                    ],
                ],
            ],
            [
                'website_id' => 114,
                'name' => 'UAE',
                'code' => 'ae',
                'default_display_currency_code' => 'AED',
                'store_list' => [
                    [
                        'code' => 'ae-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'ae-ar',
                        'name' => 'Arabic',
                    ],
                ],
            ],
            [
                'website_id' => 115,
                'name' => 'United Kingdom',
                'code' => 'gb',
                'default_display_currency_code' => 'GBP',
                'store_list' => [
                    [
                        'code' => 'gb-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 116,
                'name' => 'United States',
                'code' => 'us',
                'default_display_currency_code' => 'USD',
                'store_list' => [
                    [
                        'code' => 'us-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 117,
                'name' => 'Uruguay',
                'code' => 'uy',
                'default_display_currency_code' => 'UYU',
                'store_list' => [
                    [
                        'code' => 'uy-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 'uy-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 118,
                'name' => 'Venezuela',
                'code' => 've',
                'default_display_currency_code' => 'VEF',
                'store_list' => [
                    [
                        'code' => 've-en',
                        'name' => 'English',
                    ],
                    [
                        'code' => 've-es',
                        'name' => 'Spanish',
                    ],
                ],
            ],
            [
                'website_id' => 119,
                'name' => 'Vietnam',
                'code' => 'vn',
                'default_display_currency_code' => 'VND',
                'store_list' => [
                    [
                        'code' => 'vn-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 120,
                'name' => 'Zambia',
                'code' => 'zm',
                'default_display_currency_code' => 'ZMK',
                'store_list' => [
                    [
                        'code' => 'zm-en',
                        'name' => 'English',
                    ]
                ],
            ],
            [
                'website_id' => 105,
                'name' => 'Zimbabwe',
                'code' => 'zw',
                'default_display_currency_code' => 'ZWD',
                'store_list' => [
                    [
                        'code' => 'zw-en',
                        'name' => 'English',
                    ]
                ],
            ]
        ];
        return $websites;
    }
}
