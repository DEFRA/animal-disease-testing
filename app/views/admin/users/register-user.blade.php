@extends('layouts.master')
@section('title', 'Register New User - User Management -')
@section('content')

<h1 class="heading-large">Register new user</h1>

{{Form::open(['name'=>'registration_form','class'=>'form','autocomplete'=>'off'])}}
<fieldset>
    <legend class="visuallyhidden">Register new user</legend>

    {{Form::hidden('practice_id', $practice->getId())}}

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

    <div @if (!$errors->first('first_name')) class="row" @else class="row validation" @endif>
        {{Form::label('first_name','First name:')}}
        @if ($errors->first('first_name'))
            <span class="validation-message">{{{$errors->first('first_name')}}}</span>
        @endif
        {{ Form::text('first_name', '', array('class' => 'form-control','autocomplete' => 'off')) }}
    </div>

    <div @if (!$errors->first('last_name')) class="row" @else class="row validation" @endif>
        {{Form::label('last_name','Last name:')}}
        @if ($errors->first('last_name'))
            <span class="validation-message">{{{$errors->first('last_name')}}}</span>
        @endif
        {{ Form::text('last_name', '', array('class' => 'form-control','autocomplete' => 'off')) }}
    </div>

    <div @if (!$errors->first('email')) class="row" @else class="row validation" @endif>
        {{Form::label('email','Email:')}}
        @if ($errors->first('email'))
            <span class="validation-message">{{{$errors->first('email')}}}</span>
        @endif
        {{ Form::email('email', '', array('class' => 'form-control')) }}
    </div>

    <div @if (!$errors->first('userGroup')) class="row" @else class="row validation" @endif>
        <div class="form--half">
            <label for="userGroup">
                Admin level:
            </label>
            {{ Form::select('userGroup', $availableUserGroups, null, ['id' => 'userGroup']) }}

            @if ($errors->first('userGroup'))
                <span class="validation-message">{{{$errors->first('userGroup')}}}</span>
            @endif
        </div>
    </div>

    <div class="row">
        {{Form::submit('Register new user',['class'=>'button'],['name'=>'registration_button'])}}
        <p>{{ link_to_route('users.view', 'Cancel', ['practiceId' => $practice->getId()]) }}</p>
    </div>
    
</fieldset>

{{Form::close()}}


@stop