var testSampleType = {

    init: function () {
        $('#sample_type').change(function(){

            // Hide the test recommendations
            $('#testAdviceSearchResults').hide();
            $('#total-tests-count').hide();
            $('#total-tests-count .counter').html('0');
            $('#page-right-nav').html('');
            $('#page-left-nav').html('');

            // Get list of diseases/clinical signs for the selected species
            var speciesSelection = $('#species_recommended_selection').val();
            var disease = $('input[type="radio"][name="disease"]:checked').val();
            var sampleType = $(this).val();
            testSearch.loadRecommendedTests(speciesSelection, sampleType, disease, 1);
        });
    }
}