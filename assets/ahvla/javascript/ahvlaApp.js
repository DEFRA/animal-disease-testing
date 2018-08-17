var ahvlaApp = {

    init: function (stepIndex, fullSubmissionForm) {
        submission.init(stepIndex, fullSubmissionForm);

        //util.disableElementFormEnter();
        util.disableElementFormEnterSearchBox();

        util.bindRadioButtonsSelectedBehaviour();
    }

}