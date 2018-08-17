@extends('layouts.master')
@section('title', 'Activate your account -')
@section('content')

<h1 class="heading-large">Activate your account</h1>
<p>{{{ $message }}}</p>

<p>{{ link_to_route('login', 'Login here', '', ['class' => 'button']) }}</p>
<p>{{ link_to_route('landing', 'Go to homepage') }}</p>

<p>If you need assistance with the web application you can {{ link_to_route('help', 'contact our IT support team') }}.</p>

{{Form::close()}}

@stop