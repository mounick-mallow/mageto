define([
    'jquery',
    'mage/template'
], function ($, mageTemplate) {
    'use strict';

    return function (widget) {
        $.widget('smileEs.rangeSlider', widget, {
            _create: function () {
                this.showAdaptiveSlider = this.options.showAdaptiveSlider;
                if (this.showAdaptiveSlider) {
                    this._initAdaptiveSliderValues();
                } else {
                    this._initSliderValues();
                }

                this._createSlider();
                this._refreshDisplay();
                this.element.find(this.options.sliderBar).bind('click', this._applyUrl.bind(this));
                this.element.find('.ui-slider-handle').bind('click', this._applyUrl.bind(this));
            },

            _refreshDisplay: function () {
                let from = this._getOriginalValue(this.from);
                let to = this._getOriginalValue(this.to) - this.options.maxLabelOffset;

                if (from >= to) {
                    from = this._getOriginalValue(this.to) - this.options.maxLabelOffset;
                }

                if (this.showAdaptiveSlider && to < this._getOriginalValue(this.minValue)) {
                    to = this._getOriginalValue(this.to);
                }

                this.element.find('.from-to-label').html(this._formatLabel(from) + ' - ' + this._formatLabel(to));
            },

            _applyUrl: function () {
                let range = {
                    from : this.from * (1 / this.rate),
                    to   : this.to * (1 / this.rate)
                };

                location.href = mageTemplate(this.options.urlTemplate)(range);
            },
        });

        return $.smileEs.rangeSlider;
    };
});
