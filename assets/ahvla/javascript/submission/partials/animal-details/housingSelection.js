var housingSelection = {

    init: function () {
        housingSelection.toggleVisibility();
    },

    reload: function () {
        housingSelection.toggleVisibility();

        var selectedSpeciesCode = speciesSelection.getSelectedSpeciesCode();
        if (selectedSpeciesCode) {
            serverRequest.loadDivWithResults(
                'api/v1/housing',
                subParams.build({species: selectedSpeciesCode}),
                'housingSelectionResults',
                'housingSelectionResultRef',
                null,
                true
            );
        }
    },

    toggleVisibility: function () {
        if (speciesSelection.getSelectedSpeciesCode()) {
            util.show('housingSelection');
        } else {
            util.hide('housingSelection');
        }
    }
}