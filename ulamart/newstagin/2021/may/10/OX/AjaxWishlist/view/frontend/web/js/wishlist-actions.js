define([
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'jquery-ui-modules/widget'

], function ($, confirmation) {
    'use strict';
    var self;
    $.widget('mage.ajaxWishlist', {

        _create: function () {
            this._bind();
        },

        _bind: function () {
            self = this;
            $('body').on('click', '[data-action="wishlist-actions"]', this._wishlistAction);
        },

        _wishlistAction: function (event) {
            var productId = $(this).attr('data-product-id'),
                urlkey = window.location.origin + "/rest/V2/wishlist/ajax-wishlist-api/?productId=" + productId;
            self._ajaxcall(urlkey)
        },

        _ajaxcall: function (urlkey) {

            $.ajax({
                type: "POST",
                dataType: "json",
                url: urlkey,
            }).done(function (data) {

                if(data!==true){
                    confirmation({
                        title: 'Please login',
                        content: 'You must login or register to add items to your wishlist',
                        actions: {
                            confirm: function () {
                                window.location.href = window.location.origin +"/customer/account/login";
                            },
                        }
                    });
                }
            });
        }

    });
    return $.mage.ajaxWishlist;
});
