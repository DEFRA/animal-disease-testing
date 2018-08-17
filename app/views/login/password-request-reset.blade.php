@extends('layouts.master')
@section('title', 'Request password reset -')
@section('content')

<h1 class="heading-large">Request password reset</h1>

{{Form::open(['name'=>'request_password_reset_form', 'route'=>'request-reset-password', 'class'=>'form','autocomplete'=>'off'])}}
<fieldset>
    <legend class="visuallyhidden">Request password reset</legend>

@if ($errors->count())

    <div class="validation-summary group" role="alert">
        <h2 class="error-heading">There was a problem submitting the form</h2>
        <p>Because of the following problems:</p>

        <ul class="error-list">
        @foreach ($errors->getMessageBag()->getMessages() as $id => $messageArr)
            <li>
                @if ($id == 'login_result')
                    <a href="#email">{{{$messageArr[0]}}}</a>
                @else
                    <a href="#{{{$id}}}">{{{$messageArr[0]}}}</a>
                @endif
            </li>
        @endforeach
        </ul>
    </div>
    
@endif

    <div @if (!$errors->first('email')) class="row" @else class="row validation" @endif>
        {{Form::label('email','Email')}}
        @if ($errors->first('email'))
            <span class="validation-message">{{{$errors->first('email')}}}</span>
        @endif
        {{ Form::email('email', '', array('id' => 'email', 'class' => 'form-control')) }}
    </div>

    <div class="row">
        {{Form::submit('Send email',['id' => 'reset_pwd_button', 'class'=>'button push--top'],['name'=>'reset_pwd_button'])}}
        <p>{{ link_to_route('login', 'back') }}</p>
    </div>
    
</fieldset>

@stop