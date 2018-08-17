@extends('layouts.master')
@section('title', 'Step 4 - '.$step4Title.' - '.$submissionTypeName.' Animal Diagnostic Submission -')
@section('head')
    <script>
        $(document).ready(function () {
            ahvlaApp.init('tests', {{json_encode($fullSubmissionForm)}});
        });
    </script>
@stop

@section('content')
    @include('submission.steps')
    @include('submission.steps.partials.client-details')

    {{ Form::open(array('url'=>$subUrl->build('step-tests-submission-post'),'class'=>'form step-form','autocomplete'=>'off')) }}
    {{ Form::hidden('timestamp', time() * 1000, ['id' => 'js_timestamp']) }}

    @include('submission.validation.validation')

    <h2>4. {{{ $step4Title }}}</h2>
    <p>Select the tests that you need.</p>
    <hr/>

    {{-- Only show test recommendations for sick animals --}}
    @if(isset($fullSubmissionForm))
        @if($fullSubmissionForm->submissionType != 'routine')
            @include('submission.steps.partials.tests.need-advice-input')
        @endif
    @endif

    <div class="grid-row">
        <div class="column-two-thirds">

            <div id="test-advisor" @if(!$persistence->need_advice || $persistence->need_advice == "0")style="display:none!important;" @endif>
                @include('submission.steps.partials.tests.test-advisor', ['selectedRecommendedSpecies'=>$selectedRecommendedSpecies])
            </div>

            <div id="test-finder" @if($persistence->need_advice)style="display:none!important" @endif>
                @include('submission.steps.partials.tests.test-finder', ['selectedSpecies'=>$selectedSpecies])
            </div>

            @include('submission.steps.partials.tests.test-results')

            <div class="addProductToBasket">
                <p></p>
            </div>
        </div>

        <div id="small-basket" class="column-third push-double--top">
            @include('submission.steps.partials.tests.test-basket')
        </div>
    </div>

    <div class="row">
        {{ Form::submit('Continue',['id'=>'Continue','class'=>'button']) }}
        @include('submission.steps.partials.timeout-notification')
        <p>
            {{ Form::submit('Back',['name'=>'gotostep3', 'id'=>'back', 'class'=>'link button-as-link']) }}
        </p>
    </div>

    {{ Form::close() }}
    
@stop