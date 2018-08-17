var testSearch = {

    currentPage: 1,

    init: function () {
        $('#SearchTestsButton').click(function () {
            return false;
        });

        // search via input field
        $('input[name="test_search_input"]').keyup(function () {
            var species = $('#species_selection').val();
            testSearch.loadTests($(this).val(), species, 1);
        });

        // search via links
        $(document).on("click", ".test_search_input_link", function () {
            var page = $(this).attr('page');
            if ($('input[name="need_advice"]').val() == '1') {

                // Disable for test recommendations
                return false;
            }
            else {
                // load the actual listings
                var species = $('#species_selection').val();
                var test = $('input[name="test_search_input"]').val();
                testSearch.loadTests(test, species, page);
            }
            // we need to manually persist the current page for link clicking
            var currentPage = $('input[name="current_page"]');
            var page_ele = currentPage.val(page);
            // persist the data
            persistentForm.saveInput(page_ele);
        });

        $('#species_selection').change(function () {
            var searchString = $('input[name="test_search_input"]').val();
            if (searchString) {
                testSearch.loadTests(searchString, $(this).val());
            }
        });

        basket.init();

        // since we are adding real links for no-js functionality in page refresh, we need to disable them for ajax to work
        $('.hide-link').each(function () {
            $(this).attr("href", "javascript:void(0);");
        });

        $('#diseaseSelectionResults').on('click', 'input[type="radio"][name="disease"]', function () {
            // Hide the test recommendations
            $('#testAdviceSearchResults').hide();
            $('#total-tests-count').hide();
            $('#total-tests-count .counter').html('0');
            var species = $('#species_recommended_selection').val();
            var sampleType = '';        // Default to 'all'
            var disease = $(this).val();
            if (species && disease) {
                // Load and display the sample types
                var parameters = {};
                serverRequest.loadResults(
                    'api/v1/species/sample-types/list/' + species + '/' + disease,
                    subParams.build(parameters),
                    'sample_type_container',
                    'sample_type',
                    species,
                    sampleType,
                    disease,
                    function (parentContainerId, containerId, data, species, sampleType, disease){
                        util.updateDropdownOptions(parentContainerId, containerId, data);
                        testSearch.loadRecommendedTests(species, sampleType, disease, 1);
                    },
                    false
                );
            }
        });
    },

    // Currently ignores 'page'
    loadRecommendedTests: function (species, sampleType, disease, page) {
        var parameters = {
            'species': species,
            'sample_type': sampleType,
            'disease': disease,
            'page': page
        };

        // clear out existing page meta info
        $('#total-tests-count').hide();
        $('#total-tests-count .counter').html('0');
        $('#page-right-nav').html('');
        $('#page-left-nav').html('');

        var callback = function (data) {

            // we clear again because of ajax async
            $('#total-tests-count').hide();
            $('#total-tests-count .counter').html('0');
            $('#page-right-nav').html('');
            $('#page-left-nav').html('');

            basket.hookAddProductBehaviour();

            // add left and right pagination
            $('#page-left-nav').html('');
            if ((data['previousPage'] != undefined) && (data['previousPage'] > 0)) {
                var currentPageTxt = parseInt(data['currentPage']) - 1;
                var totalPages = data['totalPages'];
                $('#page-left-nav').html('<a page="' + data['previousPage'] + '" class="test_search_input_link" href="javascript:void(0);">Previous <span class="visuallyhidden">page</span><span class="page-numbers">' + currentPageTxt + ' of ' + totalPages + '</span></a>');
            }

            $('#page-right-nav').html('');
            if ((data['nextPage'] != undefined) && (data['nextPage'] > 0)) {
                var nextPageTxt = parseInt(data['currentPage']) + 1;
                var totalPages = data['totalPages'];
                $('#page-right-nav').html('<a page="' + data['nextPage'] + '" class="test_search_input_link" href="javascript:void(0);">Next <span class="visuallyhidden">page</span><span class="page-numbers">' + nextPageTxt + ' of ' + totalPages + '</span></a>');
            }

            // total tests found
            var numItems = $('#testAdviceSearchResults').find('.testSearchResultTemplate').length;

            if (numItems > 0) {
                $('#total-tests-count .counter').html(numItems);
                $('#total-tests-count').show();
            }
            else {
                $('#total-tests-count .counter').html('0');
                $('#total-tests-count').hide();
            }
        };

        serverRequest.loadDivWithRecommendedTests(
            'api/v1/test-recommendations',
            subParams.build(parameters),
            'testAdviceSearchResults',
            'testSearchResults',
            callback
        )
    },


    loadTests: function (filterText, species, page) {

        var parameters = {filter: filterText};

        if (species) {
            parameters.species = species;
        }

        parameters.page = page;

        // clear out existing page meta info
        $('#total-tests-count').hide();
        $('#total-tests-count .counter').html('0');
        $('#page-right-nav').html('');
        $('#page-left-nav').html('');

        var callback = function (data) {

            // we clear again because of ajax async
            $('#total-tests-count').hide();
            $('#total-tests-count .counter').html('0');
            $('#page-right-nav').html('');
            $('#page-left-nav').html('');

            $('.testSearchResultTemplate').removeClass('evenRow');
            $('.testSearchResultTemplate:odd').addClass('evenRow');
            basket.hookAddProductBehaviour();

            // add left and right pagination
            $('#page-left-nav').html('');
            if ((data['previousPage'] != undefined) && (data['previousPage'] > 0)) {
                var currentPageTxt = parseInt(data['currentPage']);
                var totalPages = data['totalPages'];
                $('#page-left-nav').html('<a page="' + data['previousPage'] + '" class="test_search_input_link" href="javascript:void(0);">Previous <span class="visuallyhidden">page</span><span class="page-numbers">' + currentPageTxt + ' of ' + totalPages + '</span></a>');
            }

            $('#page-right-nav').html('');
            if ((data['nextPage'] != undefined) && (data['nextPage'] > 0)) {
                var nextPageTxt = parseInt(data['currentPage']);
                var totalPages = data['totalPages'];
                $('#page-right-nav').html('<a page="' + data['nextPage'] + '" class="test_search_input_link" href="javascript:void(0);">Next <span class="visuallyhidden">page</span><span class="page-numbers">' + nextPageTxt + ' of ' + totalPages + '</span></a>');
            }

            // total tests found
            $('#total-tests-count').hide();
            $('#total-tests-count .counter').html('0');
            if ((data['totalItems'] != undefined) && (data['totalItems'] > 0)) {
                $('#total-tests-count').show();
                $('#total-tests-count .counter').html(data['totalItems']);
            }

            persistentForm.saveInput($('input[name="test_search_input"]'));
        };

        serverRequest.loadDivWithResultsFinal(
            'api/v1/product',
            subParams.build(parameters),
            'testSearchResults',
            'testSearchResultTemplate',
            callback
        )
    }
}