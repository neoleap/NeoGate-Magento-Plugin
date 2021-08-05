define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
],function(Component,renderList){
    'use strict';
    renderList.push({
        type : 'ipay',
        component : 'Alrajhi_iPay/js/view/payment/method-renderer/ipay-method'
    });

    return Component.extend({});
})
