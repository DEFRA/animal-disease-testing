@extends('layouts.master')
@section('title', 'Edit User - User Management -')
@section('content')

<h1 class="heading-large">Edit user</h1>

@if($throttle->isSuspended())
    <div class="row validation push-double--top push-double--bottom">
        <h3>User Suspended.</h3>
        <p>This user is suspended due to: {{{ $user->getSuspendedReason()  }}}</p>
        {{ Form::open(array('route' => ['user.unsuspend', $practice->getId()],'autocomplete'=>'off'))  }}

            {{Form::hidden('user_id', $user->getId())}}

            {{Form::submit('Remove suspension now',['class'=>'submit-like-link'],['name'=>'reset_password'])}}

        {{Form::close()}}
    </div>
@endif

{{Form::open(['name'=>'edit_user_form', 'route'=> ['user.edit', $practice->getId()], 'class'=>'form', 'autocomplete'=>'off'])}}
<fieldset>
    <legend class="visuallyhidden">Edit user</legend>

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

    <div @if (!$errors->first('userGroup')) class="row" @else class="row validation" @endif>
        <div class="form--half">
            <label for="userGroup">
                Admin level:
            </label>
            {{ Form::select('userGroup', $availableUserGroups, $user->getGroups()[0]['name'], ['id' => 'userGroup']) }}

            @if ($errors->first('userGroup'))
                <span class="validation-message">{{{$errors->first('userGroup')}}}</span>
            @endif
        </div>
    </div>

    <div class="row">
        <input type="hidden" name="is_active" value="0" />
        <label for="is_active">
            {{ Form::checkbox('is_active', 1, $user->isActivated(), ['id' => 'is_active']) }}
            Account active:
        </label>
        {{{$errors->first('is_active')}}}
    </div>

    <div class="row">
        <input type="hidden" name="is_locked" value="0" />
        <label for="is_locked">
            {{ Form::checkbox('is_locked', 1, Input::old('test', $throttle->isBanned()), ['id' => 'is_locked']) }}
            Account locked:
        </label>

        {{{$errors->first('is_locked')}}}
        <label for="banned_reason">Reason:</label>
        {{ Form::text('banned_reason', $user->getBannedReason(), ['class' => 'form-control', 'id' => 'banned_reason']) }}
    </div>

    <div class="row push--top">
        {{Form::submit('Save changes',['class'=>'button'],['name'=>'edit_button'])}}
        <p>{{ link_to_route('users.view', 'Cancel', ['practiceId' => $practice->getId()]) }}</p>
    </div>
    
</fieldset>

{{Form::close()}}

{{ Form::open(array('route' => 'request-reset-password','autocomplete'=>'off'))  }}

{{Form::hidden('email', $user->email)}}
{{Form::hidden('is_admin_reset', true)}}

{{Form::submit('Reset password',['class'=>'submit-like-link'],['name'=>'reset_password'])}}

<div class="row push--top">
    <p>{{ link_to_route('user.delete-confirm', 'Delete user', ['practiceId' => $practice->getId(), 'id' => $user->id], ['class' => 'button button-warning']) }}</p>
</div>

{{Form::close()}}
@stop