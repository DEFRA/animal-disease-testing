@extends('layouts.master')
@section('title', 'Activation Success -')
@section('content')

	<div class="submission-reference">
        <h2 class="submission-reference__heading">Account activation</h2>
        <span class="submission-reference__id">successful</span>
    </div>

    <p>{{ link_to(route('landing').'#start', 'Return to homepage') }}.</p>

@stop