var animalBreedSearch = {
    init: function () {
        animalBreedSearch.toggleHideShow();

        animalBreedSearch.hookSpeciesRadiosChange();

        $('#breedSearchInput').keyup(function () {
            animalBreedSearch.loadBreeds(
                speciesSelection.getSelectedSpeciesCode(),
                $(this).val()
            );
        });

        return false;
    },

    hookSpeciesRadiosChange: function () {
        var callback = function () {
            animalBreedSearch.toggleHideShow();

            var selectedSpeciesCode = speciesSelection.getSelectedSpeciesCode();
            if (selectedSpeciesCode) {
                animalBreedSearch.loadBreeds(
                    selectedSpeciesCode,
                    $('#breedSearchInput').val()
                );
            }
        };

        speciesSelection.hookChangeCallback(callback);
    },

    loadBreeds: function (species, filterText) {
        if (filterText.length < 2) {
            $('#breedSearchResults').hide();
            return false;
        }

        var callback = function () {
        };

        serverRequest.loadDivWithResults(
            'api/v1/animal-breed',
            subParams.build({filter: filterText, species: species}),
            'breedSearchResults',
            'breedSearchResultTemplate',
            callback
        );
    },

    toggleHideShow: function () {
        if (speciesSelection.getSelectedSpeciesCode()) {
            util.show('animalBreedSearch');
            return false;
        } else {
            util.hide('animalBreedSearch');
            return false;
        }
    }


}