var submissionSearch = {

    persistFields: [],

    init: function (persistFields) {

        submissionSearch.persistFields=persistFields;

        $('input[name="previous_submission_ref"]').keyup(function () {

            var filterText = $(this).val();

            if (filterText.length < 2) {
                $('#clinicalHistorySearchResults').hide();
                return false;
            }

            var callback = function () {
                submissionSearch.saveFields();
            };

            serverRequest.loadDivWithResults(
                '/api/v1/submission',
                subParams.build({filter: filterText, status:'Submitted,In Progress,All Tests Complete'}),
                'submissionSearchResults',
                'submissionSearchResultRefDiv',
                callback
            );

            return false;
        })

        submissionSearch.toggleVisibility();

        howGetInTouch.toggleVisibility();

        util.hideDropDown('submissionSearchResultRefDiv','submissionSearchResults');
    },

    saveFields: function() {

        for (field in this.persistFields) {
            persistentForm.saveInput( $('input[name="'+this.persistFields[field]+'"]') );
        }
    },

    toggleVisibility: function(){

        if (contactedSelection.getSelectedContactCode()==1) {
            util.show('submissionSearch');
        } else {
            util.hide('submissionSearch');
        }
    }
}