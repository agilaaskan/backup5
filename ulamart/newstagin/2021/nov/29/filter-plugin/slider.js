/**
 * Copyright � 2016 Mageplaza. All rights reserved.
 * See https://www.mageplaza.com/LICENSE.txt for license details.
 */
define([
    'jquery',
    'Magento_Catalog/js/price-utils',
    'jquery/ui',
    'Lof_LayeredNavigation/js/layer'
], function($, priceUltil) {
    "use strict";

    $.widget('lof.layerSlider', $.lof.layer, {
        options: {
            sliderElement: '#lof_price_slider',
            textElement: '#lof_price_text'
        },
        _create: function () {
            var self = this;
            $(this.options.sliderElement).slider({
                min: self.options.priceMin,
                max: self.options.priceMax,
                values: [self.options.selectedFrom, self.options.selectedTo],
                slide: function( event, ui ) {
                    self.showText(ui.values[0], ui.values[1]);
                },
                change: function(event, ui) {
                    self.ajaxSubmit(self.getUrl(ui.values[0], ui.values[1]));
                }
            });
            this.showText(this.options.selectedFrom, this.options.selectedTo);
        },

        getUrl: function(from, to){
            var ulacountry1 =  $('.ulamart-cid').html();
            if(ulacountry1 != "IN"){
                var conrupee = $("#lof_convert_rupee").text();
                if((from >= 0) && (from < 1)){
                    var from = conrupee;
                } else {
                  var from = from * conrupee;
                  from = from.toFixed(2);
                }
                var to = to * conrupee;
                to = to.toFixed(2);
            }
            return this.options.ajaxUrl.replace(encodeURI('{price_start}'), from).replace(encodeURI('{price_end}'), to);
        },

        showText: function(from, to){
            var ulacountry1 =  $('.ulamart-cid').html();
            if(ulacountry1 != "IN"){
                var conrupee = $("#lof_convert_rupee").text();
                if((from >= conrupee) && (from != 0) ) {
                    from = from / conrupee ;
                    from = from.toFixed();
                    if(from <= 0) {
                        from = 1 ;
                    }
                    to = to / conrupee ;
                    to = to.toFixed();
                }
                if((from >= 0) && (from < 1)){
                    from = 1 ;
                }
            }
            $(this.options.textElement).html(this.formatPrice(from) + ' - ' + this.formatPrice(to));
        },

        formatPrice: function(value) {
            return priceUltil.formatPrice(value, this.options.priceFormat);
        }
    });

    return $.lof.layerSlider;
});
