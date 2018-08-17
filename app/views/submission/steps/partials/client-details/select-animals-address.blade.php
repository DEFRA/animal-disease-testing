@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'animals_address_search']
)

@section('before_validation_box')
    <div id="animals-address-type" @if($persistence->animals_at_address === '1' || $persistence->animals_at_address === null)style="display:none"@endif>
@overwrite

@section('validation_box')
    <fieldset>
        <legend>Animal address or location</legend>
        <div>
            <div class="row">
                <label for="animalsSearchBox">Enter animal's address or the County Parish Holding (CPH) eg 48/234/2348:</label>
                <div>
                    <input type="text" autocomplete="off" class="form-control persistentInput" placeholder="start typing" id="animalsSearchBox" name="animals_address_search"
                           value="{{{$persistence->animals_address_search}}}">
                    <input class="search-button js-hidden" type="submit" name="refresh" value="Search">
                </div>
            </div>
            @overwrite

            @section('after_validation_box')
            <div class="clear"></div>
            <div id="animalSearchResultsContainer">
                <div id="animalSearchResults" class="block-label-parent">
                    <?php
                        $radio = 0;
                    ?>
                    @if(!count($animals_address_list))
                        @include('submission.steps.partials.client-details.result-templates.client-template', ['client_no'=>$radio, 'searchResultsRefDiv' => 'animalsSearchResultRefDiv', 'address' => 'animal_address', 'edited_name_id' => 'edited_animals_address_name_id', 'editButton' => 'editAnimalsAddressButton'])
                    @else
                        @foreach($animals_address_list as $animals_address)
                            @include('submission.steps.partials.client-details.result-templates.client-template', ['client'=>$animals_address,'client_no'=>$radio, 'searchResultsRefDiv' => 'animalsSearchResultRefDiv', 'address' => 'animal_address', 'edited_name_id' => 'edited_animals_address_name_id', 'editButton' => 'editAnimalsAddressButton'])

                            <?php
                                $radio++;
                            ?>

                        @endforeach
                    @endif
                </div>
            </div>
            <div class="row flush--bottom">
                <p>Or you can <a href="#" id="add-new-animal-address" class="js-addClient no-js-hide">create a new animal's address </a><span class="js-hidden">create new animal's address below</span>.</p>
            </div>
        </div>
    </fieldset>
    <hr />
    </div>
@overwrite

