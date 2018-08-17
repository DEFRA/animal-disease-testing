@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'client_address_search']
)

@section('after_validation_box')

@overwrite

@section('validation_box')
    <fieldset>
        <legend>Client</legend>
        <div>
            <div class="row">
                <label for="clientSearchBox">Enter the name of your client, their address, or the County Parish Holding (CPH) eg 48/234/2348:</label>
                <div>
                    <input type="text" autocomplete="off" class="form-control persistentInput" placeholder="start typing" id="clientSearchBox" name="client_address_search"
                           value="{{{$persistence->client_address_search}}}">
                    <input class="search-button js-hidden" type="submit" name="refresh" value="Search">
                </div>
            </div>
            @overwrite

            @section('after_validation_box')
            <div class="clear"></div>
            <div id="clientSearchResultsContainer">
                <div id="clientSearchResults" class="block-label-parent">
                    <?php
                        $radio = 0;
                    ?>
                    @if(!count($client_list))
                        @include('submission.steps.partials.client-details.result-templates.client-template', ['client_no' => $radio, 'searchResultsRefDiv' => 'clientSearchResultRefDiv', 'address' => 'client_address', 'edited_name_id' => 'edited_client_name_id', 'editButton' => 'editClientButton'])
                    @else
                        @foreach($client_list as $client)
                            @include('submission.steps.partials.client-details.result-templates.client-template', ['client'=>$client,'client_no' => $radio, 'searchResultsRefDiv' => 'clientSearchResultRefDiv', 'address' => 'client_address', 'edited_name_id' => 'edited_client_name_id', 'editButton' => 'editClientButton'])

                            <?php
                                $radio++;
                            ?>

                        @endforeach
                    @endif
                </div>
            </div>
            <div class="row flush--bottom">
                <p>Or you can <a href="#" id="add-new-client-address" class="js-addClient no-js-hide">create a new client</a><span class="js-hidden">create a new client below</span>.</p>
            </div>
        </div>
    </fieldset>
    <hr />
@overwrite