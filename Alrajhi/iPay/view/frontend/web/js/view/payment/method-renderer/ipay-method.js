define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Alrajhi_iPay/js/action/set-payment-method',
    ],
    function(Component,setPaymentMethod){
    'use strict';

    return Component.extend({
        defaults:{
            'template':'Alrajhi_iPay/payment/ipay'
        },
        redirectAfterPlaceOrder: false,
        
        afterPlaceOrder: function () {
            setPaymentMethod();    
        }

    });
});
