@extends('layouts.master')
@section('title', 'Request Password Reset -')
@section('content')

    <h1 class="heading-large">Too many reset password requests</h1>

    <p>There have been too many requests from the IP address "{{{$ip_address}}}" in the past {{{$minutes}}} minutes.</p>
    <p>Wait for {{{$minutes}}} minutes before trying again.</p>
    <p>{{ link_to_route('request-reset-password-form', 'Reset password') }}</p>

@stop