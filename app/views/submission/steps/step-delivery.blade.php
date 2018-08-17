@extends('layouts.master')
@section('title', 'Step 6 - '.$step6Title.' - '.$submissionTypeName.' Animal Diagnostic Submission -')
@section('head')
    <script>
        $(document).ready(function(){
            ahvlaApp.init('delivery',{{json_encode($fullSubmissionForm)}});
        });
    </script>
@stop

@section('content')

    @include('submission.steps')
    @include('submission.steps.partials.client-details')

    {{ Form::open(array('url'=>$subUrl->build('step-delivery-post'),'class'=>'form step-form','autocomplete'=>'off')) }}
    {{ Form::hidden('timestamp', time() * 1000, ['id' => 'js_timestamp']) }}

    @include('submission.validation.validation')
    <?php ($isSop) ? $stepNumber = '2' : $stepNumber = '6'; ?>
    <h2>{{{$stepNumber.'. '.$step6Title }}}</h2>

    @if(isset($addresses['singleAddress']) && !$addressObject->onlySingleAddress())
        @include('submission.steps.partials.delivery.separate-address')

        <p>You can print the address label and dispatch note when you have completed your submission.</p>

    @elseif(isset($addresses['singleAddress']))
        @include('submission.steps.partials.delivery.single-address')

        <p>You can print the address label and dispatch note when you have completed your submission.</p>
    @else
        {{ '<p>No test addresses present at the moment.</p>' }}
        <p><a href="{{{$subUrl->build('step4')}}}">Please add at least one test to the basket</a></p>
    @endif

    <div class="row">
        {{ Form::submit('Continue',['id'=>'Continue','class'=>'button']) }}
        @include('submission.steps.partials.timeout-notification')
        <p>
            {{ Form::submit('Back',['name'=>'gotostep5', 'id'=>'back', 'class'=>'link button-as-link']) }}
        </p>
    </div>

    {{ Form::close() }}

@stop