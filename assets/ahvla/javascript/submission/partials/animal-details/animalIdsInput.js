var animalIdsInput = {

    init: function () {
        $("#animals_test_qty").change(function () {
            var refElement = $("#animal_ids_box").find('.animalIdTemplateRef').first();
            var newNumberOfAnimals = parseInt($("#animals_test_qty").val());
            var existingIdsCount = $('.JSON_animal_ids:visible').length;

            // remove excess boxes
            if (newNumberOfAnimals < existingIdsCount) {
                $("#animal_ids_box").find('.animalIdTemplateRef').slice(newNumberOfAnimals - existingIdsCount).remove();
            }

            // add more boxes
            for (i = existingIdsCount; i < newNumberOfAnimals; i++) {

                if (i == 0) {
                    $("#animal_ids_box").html('');
                }
                var newElement = refElement.clone();
                newElement.show();
                newElement.find('.JSON_animalIdIndex').html(i + 1 + ':');
                newElement.find('.JSON_animal_ids').attr('name', 'animal_id' + i);
                newElement.find('.JSON_animal_ids').attr('id', 'animal_id' + i);
                newElement.find('.JSON_animal_ids').removeAttr('value');
                newElement.find('.JSON_label_animal_ids').attr('for', 'animal_id' + i);

                $("#animal_ids_box").append(newElement.prop('outerHTML'));
            }
        });

        animalIdsInput.toggleVisibility();
    },

    reload: function () {
        animalIdsInput.toggleVisibility();
    },

    toggleVisibility: function () {
        if (speciesSelection.getSelectedSpeciesCode()) {
            util.show('animalIdsInput');
        } else {
            util.hide('animalIdsInput');
        }
    }
}