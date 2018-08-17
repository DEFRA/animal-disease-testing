@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'species']
)

@section('before_validation_box')
@overwrite

@section('validation_box')
    <fieldset class="inline">
        <legend>Species</legend>
        <?php
        $radios = [];
        foreach($species as $specie){
            $radios[$specie->lims_code] = $specie->description;
        }

        $radios['_OTHER_']='Other';
        $radioGroupData = [
                'name'=> 'species',
                $radios,
                'checked'=>$persistence->species
        ];
        ?>
        <div id="speciesSelection">
            @include('submission.inputs.radiogroup', $radioGroupData)
            <input class="confirm-button js-hidden push--bottom" type="submit" name="refresh" value="Confirm choice">

            <div class="row panel-indent flush--top" id="other-species-container">
                <label for="other_species_search_input" class="row">Specify <span class="js-hidden">another</span> species:</label>
                <input type="text" name="other_species_search_input" id="other_species_search_input" class="persistentInput" placeholder="start typing" value="{{{$persistence->other_species_search_input}}}" autocomplete="off">
                <input class="search-button js-hidden" type="submit" name="refresh" value="Search">
                <div class="row" id="speciesSearchResults">
                    @if(!count($other_species_list))
                        @include('submission.steps.partials.animal-details.result-templates.other-species-template')
                        @if(isset($persistence->other_species_search_input) && $persistence->other_species_search_input != '')
                            <p>No results found.</p>
                        @endif
                    @endif
                    @foreach($other_species_list as $otherSpeciesRow)
                        @include('submission.steps.partials.animal-details.result-templates.other-species-template', ['otherSpeciesRow'=>$otherSpeciesRow])
                    @endforeach
                </div>
            </div>
            
        </div>
    </fieldset>
    <hr />
@overwrite

@section('after_validation_box')
@overwrite



