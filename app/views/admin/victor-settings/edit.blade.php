@extends('layouts.master')
@section('title', 'Edit victor settings -')
@section('content')

<h1 class="heading-large">Edit victor settings</h1>

{{ Form::model($settings, array('route' => array('settings.update', $settings->id))) }}
<fieldset>
    <legend class="visuallyhidden">Edit Information Message</legend>

    @if ($errors->count())
        <div class="validation-summary group" role="alert">
            <h2 class="error-heading">There was a problem submitting the form</h2>
            <p>Because of the following problems:</p>

            <ul class="error-list">
            @foreach ($errors->getMessageBag()->getMessages() as $id => $messageArr)
                <li>
                    <a href="#{{{$id}}}">{{{$messageArr[0]}}}</a>
                </li>
            @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <fieldset>
            <legend>System settings</legend>
            <input type="hidden" name="disableLogin" value="0" />
            <label for="disableLogin" class="block-label">
                {{ Form::checkbox('disableLogin', 1, null, ['id' => 'disableLogin', 'class' => 'disableLogin']) }}
                Disable login form
            </label>

            <label for="displayLoginPageMessage" class="block-label push--top">
                <input type="hidden" name="displayLoginPageMessage" value="0" />          
                {{ Form::checkbox('displayLoginPageMessage', 1, null, ['id' => 'displayLoginPageMessage', 'class' => 'displayLoginPageMessage']) }}
                Display custom message on login page
            </label>
            <span class="form-block form-hint">Note: You can edit this message on the {{ link_to_route('information-messages.edit', 'Mange Information Message') }} page.</span>
        </fieldset>
    </div>

    <fieldset>
        <legend>Authentication settings</legend>
        <div @if (!$errors->first('numPreviouslyDisallowedPasswords')) class="row" @else class="row validation" @endif>
            {{Form::label('numPreviouslyDisallowedPasswords', 'Password history')}}
            <span class="form-hint">Determines the number of unique passwords a user must use before an old password can be reused.</span>
            @if ($errors->first('numPreviouslyDisallowedPasswords'))
                <span class="validation-message">{{{$errors->first('numPreviouslyDisallowedPasswords')}}}</span>
            @endif
            {{ Form::text('numPreviouslyDisallowedPasswords', null, array('class' => 'form-control--small','autocomplete' => 'off')) }}
        </div>
        <div @if (!$errors->first('numDaysTilPasswordExpires')) class="row" @else class="row validation" @endif>
            {{Form::label('numDaysTilPasswordExpires', 'Number of days until password expires')}}
            @if ($errors->first('numDaysTilPasswordExpires'))
                <span class="validation-message">{{{$errors->first('numDaysTilPasswordExpires')}}}</span>
            @endif
            {{ Form::text('numDaysTilPasswordExpires', null, array('class' => 'form-control--small','autocomplete' => 'off')) }}
        </div>
        <div @if (!$errors->first('numWrongPasswordsBeforeSuspension')) class="row" @else class="row validation" @endif>
            {{Form::label('numWrongPasswordsBeforeSuspension', 'Wrong password attempts before lockout')}}
            @if ($errors->first('numWrongPasswordsBeforeSuspension'))
                <span class="validation-message">{{{$errors->first('numWrongPasswordsBeforeSuspension')}}}</span>
            @endif
            {{ Form::text('numWrongPasswordsBeforeSuspension', null, array('class' => 'form-control--small','autocomplete' => 'off')) }}
        </div>
        <div @if (!$errors->first('forgotPasswordMaxRequests')) class="row" @else class="row validation" @endif>
            {{Form::label('forgotPasswordMaxRequests', 'Forgotten password requests before lockout')}}
            @if ($errors->first('forgotPasswordMaxRequests'))
                <span class="validation-message">{{{$errors->first('forgotPasswordMaxRequests')}}}</span>
            @endif
            {{ Form::text('forgotPasswordMaxRequests', null, array('class' => 'form-control--small','autocomplete' => 'off')) }}
        </div>
        <div @if (!$errors->first('forgotPasswordMinutesSuspended')) class="row" @else class="row validation" @endif>
            {{Form::label('forgotPasswordMinutesSuspended', 'Forgotten password suspension time (minutes)')}}
            @if ($errors->first('forgotPasswordMinutesSuspended'))
                <span class="validation-message">{{{$errors->first('forgotPasswordMinutesSuspended')}}}</span>
            @endif
            {{ Form::text('forgotPasswordMinutesSuspended', null, array('class' => 'form-control--small','autocomplete' => 'off')) }}
        </div>
    </fieldset>

    <div class="row push--top">
        {{Form::submit('Save changes',['class'=>'button'],['name'=>'edit_button'])}}
        <p>{{ link_to_route('home', 'Cancel') }}</p>
    </div>    
</fieldset>
{{ Form::close() }}

@stop