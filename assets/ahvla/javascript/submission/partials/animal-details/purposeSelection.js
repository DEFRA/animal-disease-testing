var purposeSelection = {

    init: function () {
        purposeSelection.toggleVisibility();
    },

    reload: function(){
        purposeSelection.toggleVisibility();

        var selectedSpeciesCode = speciesSelection.getSelectedSpeciesCode();
        if (selectedSpeciesCode) {
            serverRequest.loadDivWithResults(
                'api/v1/species-purpose',
                subParams.build({species: selectedSpeciesCode}),
                'purposeSelectionResults',
                'purposeSelectionResultRef',
                null,
                true
            );
        }
    },

    toggleVisibility: function(){
        if (speciesSelection.getSelectedSpeciesCode()) {
            util.show('purposeSelection');
        } else {
            util.hide('purposeSelection');
        }
    }

}