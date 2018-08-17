@extends('layouts.master')
@section('title', '404 Not Found')
@section('content')

    <h1>This page has not been found</h1>

    <p>
        Please return to the {{ HTML::linkRoute('home', 'Login page') }}.
    </p>

@stop