@extends('layouts.master')
@section('title', 'Registration Confirmation-')
@section('content')

	<h1 class="heading-large">Account registration</h1>

	<section class="two-thirds">
		<div class="submission-reference">
		    <h2 class="submission-reference__heading">Registration request received</h2>
		</div>

		<h2 class="heading-medium">What happens next</h2>
		<p>We've sent your application to the Animal and Plant Health Agency support team.</p>
		<p>They will contact you either to confirm your registration, or to ask for more information.</p>
        <p>If you do not hear from us with 2 working days please email <a href="mailto:AnimalDiseaseTesting@apha.gsi.gov.uk">AnimalDiseaseTesting@apha.gsi.gov.uk</a>.</p>
        
        <a href="javascript:history.back()" class="no-js-hide">Back to previous page</a>
    	<p>{{ link_to_route('landing', 'Go to homepage') }}</p>
		<!-- <p><a href="https://www.gov.uk/service-manual/user-centred-design/resources/patterns/feedback-pages.html">What did you think of this service?</a> (takes 30 seconds)</p> -->
	</section>

@stop