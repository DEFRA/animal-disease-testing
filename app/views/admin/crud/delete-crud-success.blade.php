@extends('layouts.master')
@section('title', 'Delete Lookup Table Value(s) Success - DB Admin -')
@section('content')

    <h1 class="heading-large">Success!</h1>

    <p>Successfully deleted lookup table value(s).</p>

    <p>
        {{ link_to_route('crud.crud', 'Manage Lookup Tables') }}
    </p>

@stop