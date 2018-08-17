@extends('layouts.master')
@section('title', 'Delete Practice Success - Practice Management -')
@section('content')

<h1 class="heading-large">Success!</h1>

<p>Successfully deleted practice.</p>

<p>{{ link_to_route('practices.view', 'Back to practices') }}</p>

@stop