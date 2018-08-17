@extends('layouts.master')
@section('title', 'Register New User - User Management -')
@section('content')

<h1 class="heading-large">Register new practice user</h1>

{{Form::open(['name'=>'registration_form','class'=>'form','autocomplete'=>'off'])}}
<fieldset>
    <legend class="visuallyhidden">Register new practice user</legend>

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

    <div @if (!$errors->first('lims_code')) class="row" @else class="row validation" @endif>
        {{Form::label('lims_code','LIMS identity code eg ALPHAVET:')}}
        @if ($errors->first('lims_code'))
            <span class="validation-message">{{{$errors->first('lims_code')}}}</span>
        @endif
        {{ Form::text('lims_code', '', array('class' => 'form-control','autocomplete' => 'off')) }}
    </div>

    <div @if (!$errors->first('practice_name')) class="row" @else class="row validation" @endif>
        {{Form::label('practice_name','Practice name:')}}
        @if ($errors->first('practice_name'))
            <span class="validation-message">{{{$errors->first('practice_name')}}}</span>
        @endif
        {{ Form::text('practice_name', '', array('class' => 'form-control','autocomplete' => 'off')) }}
    </div>

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

    {{--<div class="row">
        <label for="is_admin" class="push--ends">
            {{ Form::checkbox('is_admin', 1, array('id'=>'is_admin')) }}
            Is this user an administrator?
        </label>
    </div>--}}

    <div class="row">
        {{Form::submit('Register new practice',['class'=>'button'],['name'=>'registration_button'])}}
        <p>{{ link_to_route('practices.view', 'Cancel') }}</p>
    </div>

</fieldset>

{{Form::close()}}


@stop