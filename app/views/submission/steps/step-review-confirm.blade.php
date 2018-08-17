@extends('layouts.master')
@section('title', 'Step 7 - '.$step7Title.' - '.$submissionTypeName.' Animal Diagnostic Submission -')
@section('head')
    <script>
        $(document).ready(function () {
            ahvlaApp.init('review-confirmation',{{json_encode($fullSubmissionForm)}});
        });
    </script>
@stop

@section('content')

    @include('submission.steps')
    @include('submission.steps.partials.client-details')

    {{ Form::open(array('url'=>$subUrl->build('step-review-confirm-post'),'class'=>'form step-form','autocomplete'=>'off')) }}
    {{ Form::hidden('timestamp', time() * 1000, ['id' => 'js_timestamp']) }}

    @include('submission.validation.validation')

    <article role="article" class="group">

        <?php ($isSop) ? $stepNumber = '3' : $stepNumber = '7'; ?>
        <h2>{{{$stepNumber.'. '.$step7Title }}}</h2>

        @include('submission.steps.partials.review-confirmation.test-basket')

        <label for="senders_reference" class="legend">Your reference</label>
        {{Form::text(
        'senders_reference',
        (isset($persistence->senders_reference)?$persistence->senders_reference:''),
        ['class'=>'form-control persistentInput','id'=>'senders_reference','autocomplete' => 'off'])}}

        <p><!-- TBD spacing --></p>

        <label for="contact-name" class="legend">Vet / Clinician</label>
        {{Form::text(
        'contact_name',
        (isset($persistence->contact_name)?$persistence->contact_name:$loggedUser->getFullname()),
        ['class'=>'form-control persistentInput','id'=>'contact-name','autocomplete' => 'off'])}}

        <p>We can send you confirmation by email when your results are available.</p>

        <div class="row">
        
            <fieldset>
                <legend>Your email</legend>
                <label for="email_notification" class="block-label push--bottom">
                    {{Form::checkbox('email_notification',1,$persistence->email_notification?true:false,['class'=>'persistentInput','id'=>'email_notification'])}}
                    Contact me via email
                </label>
                <div class="clear"></div>
                <div class="row panel-indent flush--top js-hidden" id="email_notification_panel">
                    <label for="email_notification_email">Enter your email:</label>
                    {{Form::text('email_notification_email',
                    (isset($persistence->email_notification_email) && !empty($persistence->email_notification_email))?$persistence->email_notification_email:$loggedUser->getUsername(),
                    ['id'=>'email_notification_email','class'=>'form-control persistentInput','autocomplete' => 'off'])}}
                </div>
            </fieldset>

        </div>
        <p>Check box if you don't want the samples you send to be used for anonymous surveillance or test validation.</p>

        <label for="samples_used_surveillance" class="block-label push-double--bottom">
            {{Form::checkbox('samples_used_surveillance',1,( ($persistence->samples_used_surveillance==false) || ($persistence->samples_used_surveillance==null)) ?false:true,['class'=>'persistentInput','id'=>'samples_used_surveillance'])}}
            I don't want my samples used
        </label>

        <div class="clear"></div>

        <div class="row">
            {{ Form::submit('Confirm order',['id'=>'confirm_order', 'class'=>'button']) }}
            @include('submission.steps.partials.timeout-notification')
            <p>
                {{ Form::submit('Back',['name'=>'gotostep6', 'id'=>'back', 'class'=>'link button-as-link']) }}
            </p>
        </div>

    </article>

    {{ Form::close() }}

@stop