@extends('layouts.master')
@section('title', 'Delete User - User Management -')
@section('content')

<h1 class="heading-large">Delete user</h1>

{{Form::open(['name'=>'delete_user_form', 'route'=> ['user.delete', $practice->getId(), $user->getId()], 'class'=>'form', 'autocomplete'=>'off'])}}
<fieldset>
    <legend class="visuallyhidden">Delete user</legend>

    {{Form::hidden('user_id', $user->getId())}}
    {{Form::hidden('practice_id', $practice->getId())}}

    <p>Confirm that you want to delete {{{ $user->getFullName() }}}...</p>

    <div class="row push--top">
        {{Form::submit('Confirm delete',['class'=>'button button-warning'],['name'=>'delete_button'])}}
        <p>{{ link_to(URL::previous(), 'Cancel') }}</p>
    </div>
    
</fieldset>
{{Form::close()}}

@stop