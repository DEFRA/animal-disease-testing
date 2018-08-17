var contactedSelection = {

    dependents: [],

    init: function(){

        $("input[name='clinical_history_same_case']").change(function(){

            submissionSearch.toggleVisibility();
            howGetInTouch.toggleVisibility();
        })
    },

    getSelectedContactCode: function () {

        return $("input[name='clinical_history_same_case']:visible:checked").val()
    },

    hookDependentObject: function (containerId) {
        contactedSelection.dependents.push(containerId);
    },

    initDependents: function () {

        jQuery.each(contactedSelection.dependents, function (key, dependentId) {
            setTimeout(dependentId + '.init()', 0)
        });
        return false;
    }

}