@extends('layouts.master')
@section('title', 'New Lookup Table Value(s) Changed Success - DB Admin -')
@section('content')

    <h1 class="heading-large">Edit Lookup Table Success</h1>

    <p>Your changes were successfully stored.</p>

    <p>
        {{ link_to_route('crud.crud', 'Manage Lookup Tables') }}
    </p>

@stop