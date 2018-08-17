var submission = {

    confirmationSubmissionAttempted: false,
    fullSubmissionForm: null,

    init: function (stepId, fullSubmissionForm) {

        submission.fullSubmissionForm = fullSubmissionForm;
        submission.confirmationSubmissionAttempted = fullSubmissionForm.confirmationAttempted;

        switch (stepId) {
            case 'animal-details':
                animalDetailsForm.init();
                break;
            case 'client-details':
                clientDetailsForm.init();
                break;
            case 'clinical-history':
                clinicalHistoryForm.init();
                break;
            case 'tests':
                testsForm.init();
                break;
            case'review-confirmation':
                reviewConfirm.init();
                break;
            case'delivery':
                deliveryForm.init();
                break;
            case'your-basket':
                yourBasketForm.init();
                break;
        }

        // cancel submission
        cancelDialog.init();

        // Bind click event to 'step' buttons
        util.bindStepButtonClickEvent();

        // bind time update to forms
        util.bindTimeUpdateToStepSubmit();
    },

    cancelSubmission: function () {
        cancelDialog.init();
    }

}