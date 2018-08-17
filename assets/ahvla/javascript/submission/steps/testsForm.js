var testsForm = {

    init: function(){
        testSwitch.init();
        testSearch.init();
        testSpecies.init();
        testSampleType.init();

        persistentForm.init('TestsForm');

        validation.init('TestsForm');
    }
}