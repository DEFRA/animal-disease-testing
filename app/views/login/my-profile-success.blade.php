@extends('layouts.master')
@section('title', 'My Profile Successful-')
@section('content')

<h1 class="heading-large">Edit My Profile Success</h1>

<p>Your changes were successful.</p>

@if($emailChanged)
	<div class="panel-indent">
		<h3>Note:</h3>
		<p>As your email address has changed you will need to reactivate your account. Check your updated email address for instructions.</p>
	</div>
@endif

<div>
    {{ link_to_route('home', 'Back to Home') }}
</div>

@stop