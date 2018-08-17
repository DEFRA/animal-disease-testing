<fieldset class="inline">
    <legend class="visuallyhidden">What species of animals do you want to test?</legend>
    <label for="species_recommended_selection" class="legend">What species of animals do you want to test?</label>
    {{Form::select(
        'species_recommended_selection',
        $test_recommended_species_list,
        //$persistence->species_selection?$persistence->species_selection:$selectedSpecies,
        $selectedRecommendedSpecies,
        ['id'=>'species_recommended_selection','class'=>'persistentInput']
    )}}
    <input class="search-button js-hidden" type="submit" name="refresh" value="Search">
</fieldset>
<hr>
<div>

    @include('submission.steps.partials.tests.disease-results', ['diseaseList'=>$diseaseOrClinicalSignsList])

</div>

{{Form::hidden('current_page',$persistence->current_page)}}