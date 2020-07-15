var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'Visionet_Checkout/js/order/place-order-mixin': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'Visionet_Checkout/js/order/set-payment-information-mixin': true
            }
        }
    }
};
