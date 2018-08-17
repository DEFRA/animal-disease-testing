@extends('layouts.master')
<?php
    if ($submissionType !== 'routine') {
        $title = $step3TitleSick;
    } else {
        $title = $step3TitleHealthy; 
    }
?>
@section('title', 'Step 3 - '.$title.' - '.$submissionTypeName.' Animal Diagnostic Submission -')
@section('head')
    <script>
        $(document).ready(function(){
            ahvlaApp.init('clinical-history',{{json_encode($fullSubmissionForm)}});
        });
    </script>
@stop

@section('content')

    @include('submission.steps')
    @include('submission.steps.partials.client-details')

    {{ Form::open(array('url'=>$subUrl->build('step-clinical-history-post'),'class'=>'form step-form','autocomplete'=>'off')) }}
    {{ Form::hidden('timestamp', time() * 1000, ['id' => 'js_timestamp']) }}

    @include('submission.validation.validation')

    <h2>3. {{{ $title }}}</h2>

    <p>
        @if ($submissionType !== 'routine')
          Tell us about the symptoms and clinical history.
        @else
          Provide information about the sample details.
        @endif
    </p>
    <hr />

    @include('submission.steps.partials.clinical-history.sample-date')

    @if ($submissionType === 'default')
        @include('submission.steps.partials.clinical-history.previous-submission')
        @include('submission.steps.partials.clinical-history.get-in-touch')
        @include('submission.steps.partials.clinical-history.disease-affected')
        @include('submission.steps.partials.clinical-history.duration-clinical-signs')
        @include('submission.steps.partials.clinical-history.clinical-signs')
        @include('submission.steps.partials.clinical-history.written-clinical-history')
    @endif

    <div class="row">
        {{ Form::submit('Continue',['id'=>'Continue','class'=>'button']) }}
        @include('submission.steps.partials.timeout-notification')
        <p>
            {{ Form::submit('Back',['name'=>'gotostep2', 'id'=>'back', 'class'=>'link button-as-link']) }}
        </p>
    </div>

    {{ Form::close() }}

@stop
