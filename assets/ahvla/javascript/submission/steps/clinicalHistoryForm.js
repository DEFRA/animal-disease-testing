var clinicalHistoryForm = {

    init: function () {

        contactedSelection.init();
        submissionSearch.init(["previous_submission_ref"]);
        howGetInTouch.init();
        clinicalSigns.init();
        clinicalHistory.init();

        persistentForm.init('ClinicalHistoryForm');

        validation.init(
            'ClinicalHistoryForm',
            ['sample_date_year', /clinical_signs_\w+/]
        );
    }
}