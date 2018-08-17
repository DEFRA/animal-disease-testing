var ageCategorySelection = {
    init: function () {
        ageCategorySelection.toggleVisibility();
        ageCategorySelection.validateAgeDetail();
    },

    reload: function(){
        ageCategorySelection.toggleVisibility();
        var selectedSpeciesCode = speciesSelection.getSelectedSpeciesCode();
        if (selectedSpeciesCode) {
            serverRequest.loadDivWithResults(
                'api/v1/species-age',
                subParams.build({species: selectedSpeciesCode}),
                'ageCategoryResults',
                'ageCategoryResultRef',
                ageCategorySelection.toggleAgeDetail,
                true
            );
            ageCategorySelection.clearAgeDetail();
            util.hide('ageDetailSelection');
        }
    },

    toggleVisibility: function(){
        util.hide('ageDetailSelection');
        if (speciesSelection.getSelectedSpeciesCode()) {
            util.show('ageCategorySelection');
            ageCategorySelection.toggleAgeDetail();
        } else {
            util.hide('ageCategorySelection');
        };
    },

    clearAgeDetail: function() {
        $('#age-detail').val('');
        $('#age-indicator').val('NONE').change();
        $('#age-is-estimate').attr('checked', false);
    },

    toggleAgeDetail: function (data) {
        var  buttons = $('#ageCategoryResults input')
                    
        buttons.each(function (i, button) {
            var buttonName = $(button).val().toLowerCase()
            
            if (buttonName !== 'na' && buttonName !== 'unknown' && buttonName !== 'none') {
               if ($(button).is(':checked')) {
                    util.show('ageDetailSelection');
               }
                $(button).click(function (e) {
                    util.show('ageDetailSelection');
                });
            } else {
                $(button).click(function (e) {
                    util.hide('ageDetailSelection');
                });
            };
        });
    },

    validateAgeDetail: function () {
        var age = $('#age-detail'),
            ageIndicator = $('#age-indicator');
            ageIsEstimate = $('#age-is-estimate');

        var isAgeDetailInvalid

         function removeError(id){
             validation.removeError(id.ageDetail);
             validation.removeError(id.ageIndicator);
         }

         function showError(id){
             validation.addError(id.ageDetail);
             validation.addError(id.ageIndicator);
         }

        function validateAgeDetail() {
                isAgeDetailInvalid = validation.isInvalidAge('#age-detail');

                // It should show an error on the Age field if it contains a invalid character
                if(isAgeDetailInvalid){
                    showError({ageDetail: '#age-detail'});
                } else {
                    ageIndicator.prop('disabled', false);
                    ageIsEstimate.prop('disabled', false);
                    removeError({ageDetail: '#age-detail'});
                }
                
                // Reset the fields
                if (age.val().length === 0) {
                   removeError({ageDetail: '#age-detail'});
                }
         }
         
         $('#age-detail').on('change', function(){
            validateAgeDetail();
         });

         $('#age-indicator').on('change', function(){
            validateAgeDetail();
         });
         validateAgeDetail();
    }
}