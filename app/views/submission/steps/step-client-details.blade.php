@extends('layouts.master')
@section('title', 'Step 1 - '.$step1Title.' - '.$submissionTypeName.' Animal Diagnostic Submission -')
@section('head')
    <script>
        $(document).ready(function(){
            ahvlaApp.init('client-details',{{ json_encode($fullSubmissionForm) }});
        });
    </script>
@stop

@section('content')

    @include('submission.steps')

    {{ Form::open(array('url'=>$subUrl->build('step-client-details-post'),'id'=>'client-details-form','class'=>'form step-form','autocomplete'=>'off')) }}
    {{ Form::hidden('timestamp', time() * 1000, ['id' => 'js_timestamp']) }}
    {{ Form::hidden('search_mode_client', $search_mode_client, ['id' => 'search_mode_client']) }}
    {{ Form::hidden('search_mode_animal', $search_mode_animal, ['id' => 'search_mode_animal']) }}

    @include('submission.validation.validation')

    <h2>1. {{{ $step1Title }}}</h2>
    <p>Tell us about the owner (your client) of the animal or animals you want to test.</p>
    <hr />

    <div id="clientSearchModeBox" @if($persistence->isIsEditClientMode() || $search_mode_client === 'clientCPHSearch' || $persistence->isIsNewClientMode()) style="display: none" @endif>
        @include('submission.steps.partials.client-details.select-client')
    </div>

    <div id="editClientModeBox" class="no-js-show" @if(!$persistence->isIsNewClientMode() && !$persistence->isIsEditClientMode())style="display: none" @endif>
        @include('submission.steps.partials.client-details.edit-new-client')
    </div>

    @include('submission.steps.partials.client-details.animals-address')

    <div id="animalAddressSearchModeBox" @if($persistence->isIsEditAnimalAddressMode() || $search_mode_animal === 'animalCPHSearch' || $persistence->isIsNewAnimalAddressMode())style="display: none" @endif>
        @include('submission.steps.partials.client-details.select-animals-address')
    </div>

    <div id="editAnimalAddressModeBox" class="no-js-show" @if(!$persistence->isIsNewAnimalAddressMode() && !$persistence->isIsEditAnimalAddressMode())style="display: none" @endif>
        @include('submission.steps.partials.client-details.animal-address-input')
    </div>

    <div class="row">
        {{ Form::submit('Continue',['class'=>'button','id'=>'continue']) }}
        @include('submission.steps.partials.timeout-notification')
    </div>

    {{ Form::close() }}

@stop