var animalDetailsForm = {

    init: function(){
        speciesSelection.hookDependentObject('animalIdsInput');
        speciesSelection.hookDependentObject('sexGroupSelection');
        speciesSelection.hookDependentObject('ageCategorySelection');
        speciesSelection.hookDependentObject('organicEnvSelection');
        speciesSelection.hookDependentObject('purposeSelection');
        speciesSelection.hookDependentObject('housingSelection');

        speciesSelection.init();
        animalBreedSearch.init();
        otherSpeciesSearch.init();

        persistentForm.init('AnimalDetailsForm');

        validation.init(
            'AnimalDetailsForm',
            ['species','age_category','purpose']
        );
    }
}