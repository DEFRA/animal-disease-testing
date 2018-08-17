@extends('layouts.master')
@section('title', 'Test advice import -')
@section('head')
@stop
@section('content')

    <h1 class="heading-large">Upload test advice</h1>

    @if(Session::has('successMessage'))
        <span style="color: green">
            {{{Session::get('successMessage', null)}}}
        </span>
    @endif


    @foreach($errors->all() as $error)
        <div style="color: #ff0000">
            {{{$error}}}
        </div>
    @endforeach


    {{ Form::open(['url'=>'test-advice/import','files'=>true,'autocomplete'=>'off']) }}

    <label for="test-advice" class="push--bottom">Upload a CSV file to update the latest test advice table.</label>

    {{Form::file('new_test_advice_file', ['id' => 'test-advice'])}}

    {{Form::submit('Upload')}}


    {{ Form::close() }}

@stop