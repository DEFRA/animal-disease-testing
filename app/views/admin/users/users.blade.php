@extends('layouts.master')
@section('title', 'User management -')
@section('content')

    <h1 class="heading-large">Manage {{{ $practice->name }}} users</h1>
    <div>
        {{ link_to_route('user.register-form', 'New user', ['practiceId' => $practice->getId()], ['class'=>'button']) }}
    </div>
    <table class="admin-users-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Last login</th>
                <td></td>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{{$user->getFullname()}}}</td>
                <td>{{{$user->getUsername()}}}</td>
                <td>{{{$user->getGroupsAsString()}}}</td>
                <td>{{{$user->getStatus()}}}<span class="status-reason">{{{$user->getReason()}}}</span></td>
                <td>{{{$user->getLastLogin()}}}</td>
                <td>
                    {{ link_to_route('user.edit-form', 'Edit', ['practiceId' => $user->getPractice()->id, 'id' => $user->getId()]) }}

                    @if ($user->isActivated())
                        @if ($loggedUser->canImpersonateUsers())
                            {{ Form::open(array('route' => 'impersonate','autocomplete'=>'off')) }}
                                {{ Form::hidden('userId', $user->getId()) }}
                                {{ Form::submit('Log in as user',['class'=>'submit-like-link'],['name'=>'impersonate_user']) }}
                            {{ Form::close() }}
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @if ($loggedUser->canManagePracticeAccounts())
    <div class="row push-double--top">
        <p>{{ link_to_route('practices.view', 'Manage Practices') }}</p>
    </div>
    @endif
@stop