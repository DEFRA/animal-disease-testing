var testSpecies = {

    init: function () {
        $('#species_recommended_selection').change(function(){

            // Show desease selection div
            $('#diseaseSelection').show();

            // Hide the test recommendations
            $('#testAdviceSearchResults').hide();
            $('#diseaseSelectionResults').hide();
            $('#total-tests-count').hide();
            $('#total-tests-count .counter').html('0');
            $('#page-right-nav').html('');
            $('#page-left-nav').html('');

            // Get list of diseases/clinical signs for the selected species
            var speciesSelection = $(this).val();
            $('#sample_type_container').addClass('hidden');

            // Get list of diseases/clinical signs for the selected species
            if (speciesSelection) {
                var parameters = {};
                serverRequest.loadDivWithResultsFinal(
                    'api/v1/species/diseases/list/' + speciesSelection,
                    subParams.build(parameters),
                    'diseaseSelectionResults',
                    'diseaseSelectionResultRef',
                    null,
                    false        // cache it
                );
            }
        });
    }
}