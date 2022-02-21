define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'razorpaymanual',
                component: 'Test_Testpayment/js/view/payment/method-renderer/razorpaymanual'
            },
            {
                type: 'storepickup',
                component: 'Test_Testpayment/js/view/payment/method-renderer/storepickup'
            },
            {
                type: 'upipayment',
                component: 'Test_Testpayment/js/view/payment/method-renderer/upipayment'
            },
            {
                type: 'bankdeposit',
                component: 'Test_Testpayment/js/view/payment/method-renderer/bankdeposit'
            }

        );
        return Component.extend({});
    }
);
