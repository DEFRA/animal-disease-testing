var speciesSelection = {
    avianSpeciesId: '_BIRD_',
    otherSpeciesId: '_OTHER_',
    dependents: [],

    init: function () {
        speciesSelection.initDependents();
    },

    dependentsCallbackHandler: function () {
        speciesSelection.reloadDependents();
    },

    getSelectedSpeciesCode: function () {
        var mainSpeciesSelection = speciesSelection._getMainSpeciesSelection();
        if (mainSpeciesSelection
            && !speciesSelection.isOther()) {
            return mainSpeciesSelection;
        }

        return $("input[name='other_species']:visible:checked").val()
    },

    isOther: function () {
        if (speciesSelection._getMainSpeciesSelection() == speciesSelection.otherSpeciesId) {
            return true;
        }
        return false;
    },

    _getMainSpeciesSelection: function () {
        return $("input[name='species']:checked").val();
    },

    hookChangeCallback: function (callback) {
        $("body").off('change', "input[name='species']", callback);
        $("body").on('change', "input[name='species']", callback);
        $("body").off('change', "input[name='other_species']", callback);
        $("body").on('change', "input[name='other_species']", callback);

        $("body").off('change', "input[name='species']", speciesSelection.dependentsCallbackHandler);
        $("body").on('change', "input[name='species']", speciesSelection.dependentsCallbackHandler);
        $("body").off('change', "input[name='other_species']", speciesSelection.dependentsCallbackHandler);
        $("body").on('change', "input[name='other_species']", speciesSelection.dependentsCallbackHandler);

    },

    hookDependentObject: function (containerId) {
        speciesSelection.dependents.push(containerId);
    },

    initDependents: function () {
        jQuery.each(speciesSelection.dependents, function (key, dependentId) {
            setTimeout(dependentId + '.init()', 0)
        });
        return false;
    },

    reloadDependents: function () {
        jQuery.each(speciesSelection.dependents, function (key, dependentId) {
            setTimeout(dependentId + '.reload()', 0)
        });
        $('#organicEnvSelection').find('input[type="radio"]:checked').prop('checked', false);
    }
}
