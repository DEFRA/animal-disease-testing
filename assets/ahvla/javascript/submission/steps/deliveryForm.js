var deliveryForm = {

    init: function(){

        sendSamples.init();

        persistentForm.init('DeliveryForm');

        validation.init(
            'DeliveryForm',
            ['send_samples_package']
        );
    }
}