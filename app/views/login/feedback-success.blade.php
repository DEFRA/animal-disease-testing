@extends('layouts.master')

@section('content')
    <h2>Feedback</h2>

    <p>Thanks for your feedback.</p>

    @if (isset($redirectTo))
    <p><a href="{{{ $redirectTo }}}">Back to previous page</a></p>
    @else
    <p><a href="javascript:history.back()" class="no-js-hide">Back to previous page</a></p>
    @endif

    <p>{{ link_to_route('landing', 'Go to homepage') }}</p>
@stop