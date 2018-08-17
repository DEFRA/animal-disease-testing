@extends('layouts.master')
@section('title', 'New Lookup Table Value(s) Added Success - DB Admin -')
@section('content')

    <h1 class="heading-large">Create Lookup Table Values Success</h1>

    <p>The lookup table values were added.</p>

    <p>
        {{ link_to_route('crud.crud', 'Manage Lookup Tables') }}
    </p>

@stop