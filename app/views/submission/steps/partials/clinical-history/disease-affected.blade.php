<fieldset>
    <legend>How has the disease affected the animals (optional)</legend>
    <div class="grid-row">
        <div class="column-half">
            <div class="group">
                <label for="disease_affect_number_in_herd">Total number of animals:</label>
                {{ Form::text('disease_affect_number_in_herd', $persistence->disease_affect_number_in_herd, ['id'=>'disease_affect_number_in_herd','class'=>'persistentInput js-numeric','autocomplete' => 'off', 'maxlength' => '5']); }}
            </div>
            <div class="group">
                <label for="disease_affect_number_breeding_animals">Number of breeding animals:</label>
                {{ Form::text('disease_affect_number_breeding_animals', $persistence->disease_affect_number_breeding_animals, ['id'=>'disease_affect_number_breeding_animals','class'=>'persistentInput js-numeric','autocomplete' => 'off', 'maxlength' => '5']); }}
            </div>
            <div class="group">
                <label for="disease_affect_number_affected_group">Number in affected group:</label>
                {{ Form::text('disease_affect_number_affected_group', $persistence->disease_affect_number_affected_group, ['id'=>'disease_affect_number_affected_group','class'=>'persistentInput js-numeric','autocomplete' => 'off', 'maxlength' => '5']); }}
            </div>
        </div>
        <div class="column-half">
            <label for="disease_affect_number_affected_group_dead">Number affected including dead:</label>
            {{ Form::text('disease_affect_number_affected_group_dead', $persistence->disease_affect_number_affected_group_dead, ['id'=>'disease_affect_number_affected_group_dead','class'=>'persistentInput js-numeric','autocomplete' => 'off', 'maxlength' => '5']); }}
            <label for="disease_affect_number_dead">Number dead:</label>
            {{ Form::text('disease_affect_number_dead', $persistence->disease_affect_number_dead, ['id'=>'disease_affect_number_dead','class'=>'persistentInput js-numeric','autocomplete' => 'off', 'maxlength' => '5']); }}
        </div>
    </div>
</fieldset>
<hr />