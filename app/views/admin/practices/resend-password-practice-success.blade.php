@extends('layouts.master')
@section('title', 'Welcome Email sent - Practice Management -')
@section('content')

    <h1 class="heading-large">Email sent</h1>

    <p>A welcome email was resent to {{{$user->email}}}.</p>

    <div>
        {{ link_to_route('practices.view', 'Manage Practices', [], []) }}
    </div>

@stop