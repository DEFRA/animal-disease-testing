@extends('layouts.master')
@section('title', 'Password Reset -')
@section('content')

<h1 class="heading-large">Reset your password</h1>

{{ Form::open(array('name'=>'reset-password-form', 'id'=>'reset-password-form', 'route' => 'reset-password', 'class'=>'form', 'autocomplete' => 'off'))  }}
<fieldset>
    <legend class="visuallyhidden">Reset password</legend>


@if ($errors->count())

    <div class="validation-summary group" role="alert">
        <h2 class="error-heading">There was a problem submitting the form</h2>
        <p>Because of the following problems:</p>

        <ul class="error-list">
        @foreach ($errors->getMessageBag()->getMessages() as $id => $messageArr)
            <li>
                @if ($id == 'login_result')
                    {{{$messageArr[0]}}}
                @else
                    <a href="#{{{$id}}}">{{{$messageArr[0]}}}</a>
                @endif
            </li>
        @endforeach
        </ul>
    </div>
    
@endif

    {{Form::hidden('reset_password_code', $resetPasswordCode)}}
    {{Form::hidden('id', $user->getId())}}

    <div @if (!$errors->first('email')) class="row" @else class="row validation" @endif>
        {{Form::label('email','Verify email')}}
        @if ($errors->first('email'))
            <span class="validation-message">{{{$errors->first('email')}}}</span>
        @endif
        {{ Form::email('email', '', array('class' => 'form-control','autocomplete'=>'off')) }}
    </div>

    <div @if (!$errors->first('password')) class="row" @else class="row validation" @endif>
        <label for="password" class="form-label">
            New password
            <span class="form-hint">
                Your password must:
                <ul>
                    <li>begin with a letter</li>
                    <li>contain at least 1 digit</li>
                    <li>contain at least 1 upper-case letter</li>
                    <li>contain at least 1 lower-case letter</li>
                    <li>be at least 8 characters long</li>
                </ul>
            </span>
        </label>
        @if ($errors->first('password'))
            <span class="validation-message">{{{$errors->first('password')}}}</span>
        @endif
        {{ Form::password('password', array('id' => 'password','class' => 'form-control','autocomplete'=>'off')) }}
    </div>

    <div @if (!$errors->first('password_confirmation')) class="row" @else class="row validation" @endif>
        {{Form::label('password_confirmation','Confirm password')}}
        @if ($errors->first('email'))
            <span class="validation-message">{{{$errors->first('password_confirmation')}}}</span>
        @endif
        {{ Form::password('password_confirmation', array('id' => 'password_confirmation','class' => 'form-control','autocomplete'=>'off')) }}
    </div>

    <div class="row">
        {{Form::submit('Reset password',['class'=>'button push--top'])}}
    </div>

</fieldset>
{{Form::close()}}

@stop