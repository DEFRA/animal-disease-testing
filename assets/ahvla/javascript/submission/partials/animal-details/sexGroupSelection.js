var sexGroupSelection = {
    init: function () {
        sexGroupSelection.toggleVisibility();
    },

    reload: function(){
        sexGroupSelection.toggleVisibility();

        var selectedSpeciesCode = speciesSelection.getSelectedSpeciesCode();
        if (selectedSpeciesCode) {
            serverRequest.loadDivWithResults(
                'api/v1/sex-group',
                subParams.build({species: selectedSpeciesCode}),
                'sexGroupSelectionResults',
                'sexGroupSelectionResultRef',
                null,
                true
            );
        }
    },

    toggleVisibility: function(){
        if (speciesSelection.getSelectedSpeciesCode()) {
            util.show('sexGroupSelection');
        } else {
            util.hide('sexGroupSelection');
        }
    }
}