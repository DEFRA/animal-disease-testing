@extends('layouts.master')
@section('title', 'Edit information message - Successful -')
@section('content')

<h1 class="heading-large">Edit successful</h1>

<p>Your changes were successful.</p>

<div>
    {{ link_to(URL::previous(), 'Back') }}
</div>

@stop