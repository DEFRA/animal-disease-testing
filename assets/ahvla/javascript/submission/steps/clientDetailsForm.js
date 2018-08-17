var clientDetailsForm = {
    phpFormClassName: 'ClientDetailsForm',

    init: function () {
        clientSearch.init([]);
        animalsAtAddress.init();

        persistentForm.init(clientDetailsForm.phpFormClassName);
        validation.init(
            clientDetailsForm.phpFormClassName,
            ['client_address', 'animal_location', 'animals_at_address', 'edited_client_name',
                'edited_client_address_line1', 'edited_client_cphh', 'animal_cphh']
        );
    }
}