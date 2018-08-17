var yourBasketForm = {

    init: function () {
        basket.init();
        testPooling.init();
        animalsAtAddress.init();
        persistentForm.init('YourBasketForm');
        validation.init('YourBasketForm', [/sampleTypesSelect_\w+/, /productOption_\w+_\w+/]);
    }
}