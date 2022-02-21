/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define(["jquery/ui","jquery"], function(Component, $){
    return function(config, element){
        var minicart = $(element);
        minicart.on('contentLoading', function () {
            minicart.on('contentUpdated', function () {
                minicart.find('[data-role="dropdownDialog"]').dropdownDialog("open");

                setTimeout(function() {
                    $('[data-block="minicart"]').find('[data-role="dropdownDialog"]').dropdownDialog("close");
                }, 6000);


            });
        });
    }


});