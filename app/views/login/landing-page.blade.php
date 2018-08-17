@extends('layouts.master')
@section('title', 'Animal disease testing service')
@section('head')
<script>
    $(document).ready(function(){
        submission.cancelSubmission();
        search.init({{json_encode($filterForm)}});
    });
</script>
@stop

@section('content')

@if(Session::has('confirm'))
    {{{ Session::get('confirm') }}}
@endif

<h2>New submission</h2>
<p>Start a new sick animal submission or healthy animal submission.</p>

<div class="push--bottom">
    <a id="start-sick-animal" href="/start" class="button button-get-started">Sick animal submission</a>
</div>

<div>
    <a id="start-healthy-animal" href="/start?stype=routine" class="button button-get-started">Healthy animal submission</a>
</div>

<div class="clear"></div>
<br />
<hr />
<h2>View completed submissions or edit draft submissions</h2>

    @include('login.partials.submissions-filter')

    <div id="result">
        @include('login.partials.submissions')
    </div>

@stop