@extends('layouts.master')
@section('title', 'Password Change Success -')
@section('content')

    <div class="submission-reference">
        <h2 class="submission-reference__heading">Password changed</h2>
        <span class="submission-reference__id">successfully</span>
    </div>

    <p>{{ link_to(route('landing').'#start', 'Return to homepage') }}.</p>

@stop