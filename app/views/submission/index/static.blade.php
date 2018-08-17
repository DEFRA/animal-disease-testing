@extends('layouts.master')
@section('title', 'Cancel Submission -')
@section('head')
<script>
    $(document).ready(function(){

    });
</script>
@stop

@section('content')

@include('submission.steps')
@include('submission.steps.partials.client-details')

<h2>Cancel submission</h2>

<p>
    All submission details will be lost.<br />
    Are you sure you want to cancel?
<p>

{{Form::open(['url' => 'cancel-submission', 'class' => 'idp-option', 'autocomplete' => 'off', 'method' => 'post'])}}
    <button title="Cancel Submission" name="cancel-submission" value="cancel-submission" class="button" type="submit">Yes, cancel this submission</button>
    <input name="submission-id" value="{{{ $id }}}" type="hidden">
{{Form::close()}}

<a href="{{ URL::previous() }}" class="js-dialog-close">No, continue with submission</a>


@stop
