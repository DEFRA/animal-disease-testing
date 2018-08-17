var otherSpeciesSearch = {
    visible: false,

    init: function () {
        otherSpeciesSearch.toggleHideShow();

        $("input[name='species']").change(function () {

            otherSpeciesSearch.toggleHideShow();

            if (otherSpeciesSearch.visible) {
                otherSpeciesSearch.loadRelatedSpecies(
                    $("input[name='species']:checked").val(),
                    $('#other_species_search_input').val()
                );
            }
        });

        $('#other_species_search_input').keyup(function () {
            otherSpeciesSearch.loadRelatedSpecies(
                $("input[name='species']:checked").val(),
                $(this).val()
            );
        });
    },

    loadRelatedSpecies: function (species, filterText) {
        if (filterText.length < 2) {
            $('#speciesSearchResults').hide();
            return false;
        }

        var params = {filter: filterText};

        if (speciesSelection.isOther()) {
            params.less_common = 1;
        } else {
            $('#speciesSearchResults').hide();
            return false;
        }

        var callback = function () {
            animalBreedSearch.hookSpeciesRadiosChange();
        };

        serverRequest.loadDivWithResults(
            'api/v1/species',
            subParams.build(params),
            'speciesSearchResults',
            'speciesSearchResultTemplate',
            callback
        );
    },

    toggleHideShow: function () {

        if (speciesSelection.isOther()) {

            util.show('other-species-container');
            otherSpeciesSearch.visible = true;
            return false;
        } else {
            util.hide('other-species-container');
            otherSpeciesSearch.visible = false;
            return false;
        }
    }

}