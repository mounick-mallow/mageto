define(['jquery', 'uiComponent', 'ko'], function ($, Component, ko) {
        'use strict';
        return Component.extend({
            initialize: function (config) {
                this._super();
                this.tickets = JSON.parse(config.tickets)?.items;
            },

            getTimeSinceCreatingDate: function (createdAt) {
                let date = new Date(createdAt);
                let offset = date.getTimezoneOffset() * 60 * 1000;
                let localDate = new Date(date.getTime() - offset);
                let now = new Date();
                let diff = now - localDate;
                let hours = Math.floor(diff / (1000 * 60 * 60));
                let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

                if (hours < 1) {
                    return minutes + " min";
                } else {
                    return hours + " hr";
                }
            },

            toggleMessagesPopup: function () {
                $('.tickets-icon-content-wrapper').toggleClass('active');
            }
        });
    }
);
