@extends('layouts.master')
@section('title', 'My Profile -')
@section('content')

<h1 class="heading-large">My profile</h1>


{{Form::open(['name'=>'my_profile_form', 'route'=> ['my-profile'], 'class'=>'form', 'autocomplete'=>'off'])}}
<fieldset>
    <legend class="visuallyhidden">Edit my profile</legend>

    {{Form::hidden('user_id', $user->getId())}}
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
        {{ Form::text('first_name', $user->first_name, array('class' => 'form-control','autocomplete' => 'off')) }}
    </div>

    <div @if (!$errors->first('last_name')) class="row" @else class="row validation" @endif>
        {{Form::label('last_name','Last name:')}}
        @if ($errors->first('last_name'))
            <span class="validation-message">{{{$errors->first('last_name')}}}</span>
        @endif
        {{ Form::text('last_name', $user->last_name, array('class' => 'form-control','autocomplete' => 'off')) }}
    </div>

    <div @if (!$errors->first('email')) class="row" @else class="row validation" @endif>
        {{Form::label('email','Email:')}}
        @if ($errors->first('email'))
            <span class="validation-message">{{{$errors->first('email')}}}</span>
        @endif
        {{ Form::email('email', $user->email, array('class' => 'form-control')) }}
    </div>

    <div class="row push--top">
        {{Form::submit('Save changes',['class'=>'button'],['name'=>'edit_button'])}}
        <p>{{ link_to(URL::previous(), 'Cancel') }}</p>
    </div>
    
</fieldset>

{{Form::close()}}

{{ Form::open(array('route' => 'request-reset-password','autocomplete'=>'off'))  }}

{{Form::hidden('email', $user->email)}}
{{Form::hidden('is_admin_reset', false)}}

{{Form::submit('Reset my password',['class'=>'submit-like-link'],['name'=>'reset_password'])}}

{{Form::close()}}

@stop