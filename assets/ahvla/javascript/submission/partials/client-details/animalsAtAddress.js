var animalsAtAddress = {

    init: function(){

        $("input[name='animals_at_address']").change(function(){
            var getChecked = $("input[name='animals_at_address']:checked").val();
            if (getChecked == 0) {
                // NO
                $('#animals-address-type').show();
                $('#animals-address-prev').hide();
                $('#animalAddressSearchModeBox').show();
                if($('#animalSearchResultsContainer').children().length === 0) {
                    $('#animalSearchResults').detach().appendTo('#animalSearchResultsContainer');
                }
            } else {
                // YES
                $('#animals-address-type').hide();
                $('#editAnimalAddressModeBox').hide();
                $('#animals-address-prev').show();
            }
        });
    }
}