@extends('layouts.master')
@section('title', 'Step 2 - '.$step2Title.' - '.$submissionTypeName.' Animal Diagnostic Submission -')
@section('head')
    <script>
        $(document).ready(function(){
            ahvlaApp.init('animal-details',{{json_encode($fullSubmissionForm)}});
        });
    </script>
@stop

@section('content')

    @include('submission.steps')
    @include('submission.steps.partials.client-details')

    {{ Form::open(array('url'=>$subUrl->build('step-animal-details-post'),'class'=>'form step-form','id'=>'step-form','autocomplete'=>'off')) }}
    {{ Form::hidden('timestamp', time() * 1000, ['id' => 'js_timestamp']) }}

    @include('submission.validation.validation')

    <h2>2. {{{ $step2Title }}}</h2>
    <p>Tell us about the animals you want to test.</p>
    <hr />

    @include('submission.steps.partials.animal-details.speciesSelection')
    @include('submission.steps.partials.animal-details.animalBreedSearch')
    @include('submission.steps.partials.animal-details.animalIdsInput')
    @include('submission.steps.partials.animal-details.sexGroupSelection')
    @include('submission.steps.partials.animal-details.ageCategorySelection')
    @include('submission.steps.partials.animal-details.organicEnvSelection')
    @include('submission.steps.partials.animal-details.purposeSelection')
    @include('submission.steps.partials.animal-details.housingSelection')

    <div class="row">
        {{ Form::submit('Continue',['id'=>'Continue','class'=>'button']) }}
        @include('submission.steps.partials.timeout-notification')
        <p>
            {{ Form::submit('Back',['name'=>'gotostep1', 'id'=>'back', 'class'=>'link button-as-link']) }}
        </p>
    </div>

    {{ Form::close() }}

@stop