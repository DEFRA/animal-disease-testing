@extends('layouts.master')
@section('title', 'Request password reset -')
@section('content')

<h1 class="heading-large">Reset password link has expired</h1>

<p>If you need to reset your password choose forgotten password from the sign in page.</p>
<p>{{ link_to_route('login', 'Sign in') }}</p>

@stop