@extends('layouts.master')
@section('title', 'New User Registration Successful - User Management -')
@section('content')

    <h2>Registration Success</h2>

    <p>New user {{{ $fullname }}} has been added to your practice.</p>
    <p>An email has been sent with information on how to sign in to use the service.</p>

    <p>{{ link_to_route('users.view', 'Manage users', ['practiceId' => $practice->getId()]) }}</p>

@stop