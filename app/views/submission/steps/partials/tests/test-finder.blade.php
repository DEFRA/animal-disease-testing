
<fieldset>
    <legend class="visuallyhidden">Test related terms</legend>
    <label for="test_search_input">Enter any terms related to the tests you need eg name of test, type of test, sample type or disease.</label>
    {{Form::text(
        'test_search_input',
        $persistence->test_search_input,
        ['id'=>'test_search_input', 'placeholder'=>'start typing', 'class'=>'persistentInput form-control form-control--width-auto','autocomplete'=>'off'])}}

    {{ Form::hidden(
        'species_selection',
        isset( $selectedSpecies ) ? $selectedSpecies : '' ,
        ['id'=>'species_selection','class'=>'persistentInput']
    ) }}

    {{Form::hidden('current_page',$persistence->current_page)}}
    <input class="search-button js-hidden" type="submit" name="refresh" value="Search">

</fieldset>
<hr />