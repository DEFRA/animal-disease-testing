var organicEnvSelection = {
    init: function () {
        organicEnvSelection.toggleVisibility();
    },

    reload: function(){
        organicEnvSelection.toggleVisibility();
    },

    toggleVisibility: function(){
        if (speciesSelection.getSelectedSpeciesCode()) {
            util.show('organicEnvSelection');
        } else {
            util.hide('organicEnvSelection');
        }
    }
}