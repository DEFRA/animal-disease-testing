@extends('layouts.master')
@section('title', 'Edit Practice - Practice Management -')
@section('content')

    <h1 class="heading-large">Edit practice</h1>

    {{Form::open(['name'=>'edit_practice_form', 'route'=>'practice.edit', 'class'=>'form', 'autocomplete'=>'off'])}}
    <fieldset>
        <legend class="visuallyhidden">Edit practice</legend>
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
            {{Form::label('lims_code','LIMS identity code eg ALPHAVET')}}
            @if ($errors->first('lims_code'))
                <span class="validation-message">{{{$errors->first('lims_code')}}}</span>
            @endif
            {{ Form::text('lims_code', $practice->lims_code, array('class' => 'form-control','autocomplete' => 'off')) }}
        </div>

        <div @if (!$errors->first('practice_name')) class="row" @else class="row validation" @endif>
            {{Form::label('practice_name','Practice name')}}
            @if ($errors->first('practice_name'))
                <span class="validation-message">{{{$errors->first('practice_name')}}}</span>
            @endif
            {{ Form::text('practice_name', $practice->name, array('class' => 'form-control','autocomplete' => 'off')) }}
        </div>

        <div @if (!$errors->first('first_name')) class="row" @else class="row validation" @endif>
            {{Form::label('first_name','First name')}}
            @if ($errors->first('first_name'))
                <span class="validation-message">{{{$errors->first('first_name')}}}</span>
            @endif
            {{ Form::text('first_name', $user->first_name, array('class' => 'form-control','autocomplete' => 'off')) }}
        </div>

        <div @if (!$errors->first('last_name')) class="row" @else class="row validation" @endif>
            {{Form::label('last_name','Last name')}}
            @if ($errors->first('last_name'))
                <span class="validation-message">{{{$errors->first('last_name')}}}</span>
            @endif
            {{ Form::text('last_name', $user->last_name, array('class' => 'form-control','autocomplete' => 'off')) }}
        </div>

        <div @if (!$errors->first('email')) class="row" @else class="row validation" @endif>
            {{Form::label('email','Email')}}
            @if ($errors->first('email'))
                <span class="validation-message">{{{$errors->first('email')}}}</span>
            @endif
            {{ Form::email('email', $user->email, array('class' => 'form-control')) }}
        </div>

        <div class="row push--top">
            {{Form::submit('Save changes',['class'=>'button'],['name'=>'edit_button'])}}
            <p>{{ link_to_route('practices.view', 'Cancel') }}</p>
        </div>

        <div class="row push--top">
            <p>{{ link_to_route('practice.delete-confirm', 'Delete practice', ['practiceId' => $practice->getId()], ['class' => 'button button-warning']) }}</p>
        </div>

    </fieldset>

    {{Form::close()}}

@stop