define([
        'jquery',
        'mage/translate',
        'jquery/validate'
    // eslint-disable-next-line strict
    ], function($, $t){
        return function (target) {
            $.validator.addMethod(
                'validate-cron',
                function (value) {
                    'use strict';
                    console.log(value);
                    if (value.length) {
                        // eslint-disable-next-line max-len
                        let cronRegex = new RegExp(/^(\*|([0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])|\*\/([0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])) (\*|([0-9]|1[0-9]|2[0-3])|\*\/([0-9]|1[0-9]|2[0-3])) (\*|([1-9]|1[0-9]|2[0-9]|3[0-1])|\*\/([1-9]|1[0-9]|2[0-9]|3[0-1])) (\*|([1-9]|1[0-2])|\*\/([1-9]|1[0-2])) (\*|([0-6])|\*\/([0-6]))$/);

                        console.log(cronRegex.test(value));
                        return cronRegex.test(value);
                    }
                        return $t(
                            "Please write correct cron schedule." +
                            " You can look how to set schedule on the site https://crontab.guru/"
                        );
                },
            );

            return target;
        };
});
