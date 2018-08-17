@extends('layouts.master')
@section('title', 'Edit Practice Successful - Practice Management -')
@section('content')

    <h1 class="heading-large">Edit Practice Success</h1>

    <p>Your changes were successfully stored.</p>

    <div>
        {{ link_to_route('practices.view', 'Manage Practices', [], []) }}
    </div>

@stop