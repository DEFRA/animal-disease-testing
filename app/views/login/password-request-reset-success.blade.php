@extends('layouts.master')
@section('title', 'Request Password Reset -')
@section('content')

    @if ($isAdminResetUserPwd)

        <h2>Reset user APHA testing service account password</h2>

        <p>Thank you.</p>
        <p>We've emailed a link to {{{$email}}} so that they can reset their password.</p>
        <p><a href="{{ URL::previous() }}">Back</a></p>

    @else

        <h2>Reset your APHA testing service account password</h2>

        <p>Thank you.</p>
        <p>We've emailed a link to {{{$email}}} so that you can reset your password.</p>
        <p>If you haven't received the email within a few minutes, please check your spam, bulk or junk email folder – it may have been mistakenly blocked by your email system.</p>
        <p>If it doesn't arrive at all, please check that you've given us the correct email address for your APHA Testing Service – we'll only send a password reset link to the email address belonging to an account.</p>
        <p>{{ link_to_route('login-form', 'Sign in') }}</p>

    @endif

@stop