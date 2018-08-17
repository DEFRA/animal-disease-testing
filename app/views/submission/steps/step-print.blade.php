@extends('layouts.master')
@section('title', 'Step 8 - '.$step8Title.' - '.$submissionTypeName.' Animal Diagnostic Submission -')

@section('content')

    @include('submission.steps')

    <?php ($isSop) ? $stepNumber = '4' : $stepNumber = '8'; ?>
    <h2>{{{$stepNumber.'. '.$step8Title }}}</h2>

    <div class="submission-reference">
        <h2 class="submission-reference__heading">Submission complete</h2>
        <p class="submission-reference__title">Submission reference</p>
        <span class="submission-reference__id">{{{$submissionId}}}</span>
    </div>

    @if($deliveryForm->send_samples_package=='separate')

        @if(isset($addresses->deliveryAddresses['separateAddresses']))

            @foreach($addresses->deliveryAddresses['separateAddresses'] as $i=>$delivery)

                @include('submission.steps.partials.print.print-separate')

            @endforeach

        @endif

    @elseif($deliveryForm->send_samples_package=='together')

        @if(isset($addresses->deliveryAddresses['singleAddress']))
            @include('submission.steps.partials.print.print-single')
        @endif

    @endif

    <p class="push-double--top push-double--bottom"><a href="https://www.gov.uk/done/animal-disease-testing" target="_blank">What did you think of this service?</a> (takes 30 seconds)</p>

    <br/>

    {{Form::open(['url'=>$subUrl->build('step-print-documents-post'),'class'=>'form step-form','autocomplete'=>'off'])}}
    {{ Form::hidden('timestamp', time() * 1000, ['id' => 'js_timestamp']) }}
    {{Form::submit('Finish',['name'=>'finishSubmission','id'=>'finishSubmission', 'class'=>'button']) }}

    {{Form::close()}}

@stop