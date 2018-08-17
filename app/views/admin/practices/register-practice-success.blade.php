@extends('layouts.master')
@section('title', 'New Practice Registration Successful - User Management -')
@section('content')

    <h2>Registration Success</h2>

    <p>New user {{{ $fullname }}} in practice has been added.</p>
    <p>An email has been sent to the user with information on how to sign in to use the service.</p>

    <p>{{ link_to_route('practices.view', 'Manage practices') }}</p>

@stop