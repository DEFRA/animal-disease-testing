@extends('layouts.master')
@section('title', 'User Management -')
@section('content')

    <h1 class="heading-large">User Accounts</h1>
    <div>
        {{ link_to_route('user.register-form', 'New User', [], ['class'=>'button']) }}
    </div>
    <table class="users-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Active</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{{$user->getFullname()}}}</td>
                <td>{{{$user->getUsername()}}}</td>
                <td>{{{$user->getGroupsAsString()}}}</td>
                <td>{{{$user->isActivated()?'Yes':'No'}}}</td>
                <td>
                    {{ link_to_route('user.edit-form', 'Edit', ['id'=>$user->getId()]) }}

                    @if ($user->isActivated())

                        {{ Form::open(array('route' => 'request-reset-password','autocomplete'=>'off'))  }}

                        {{Form::hidden('email', $user->getUsername())}}
                        {{Form::hidden('is_admin_reset', true)}}

                        {{Form::submit('Reset password',['class'=>'submit-like-link'],['name'=>'reset_password'])}}

                        {{Form::close()}}

                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>  
    </table>
@stop