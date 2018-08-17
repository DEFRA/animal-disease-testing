var testSwitch = {

    init: function () {

        // switch between test advisor and test search
        $('input[name="need_advice"]').change(function () {

            // Clear test recommendations
            $('#total-tests-count').hide();
            $('#total-tests-count .counter').html('0');
            $('#page-right-nav').html('');
            $('#page-left-nav').html('');

            // YES - I need advice
            if ($(this).val() == '1') {
                $('#testSearchResults, #test-finder, .js-dont-need-advice').hide();
                $('#test-advisor, .js-need-advice').show();

                // Ensure the change event gets triggered
                $('input[name="test_search_input"]').val('').change();
            }
            // NO - I don't need advice
            else {
                $('#testAdviceSearchResults, #test-advisor, .js-need-advice').hide();
                $('#test-finder, .js-dont-need-advice').show();

                // Reset disease and sample type and ensure the 'change' event fires
                var selectedDiseaseRadioButtons = $('input[type="radio"][name="disease"]:checked');
                if (selectedDiseaseRadioButtons.length) {
                    selectedDiseaseRadioButtons.prop('checked', false).change();
                }
            }
        });
    }
}
