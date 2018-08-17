@extends('layouts.master')
@section('title', 'Help -')

@section('content')

	<div class="grid-row">
	    <div class="help">
	    	<h1 class="heading-large">Help using this service</h1>

		    <p class="push-double--bottom push--top">If you would like to discuss a clinical case, selection of tests, or to discuss results, contact your designated <a href="http://ahvla.defra.gov.uk/vet-gateway/surveillance/diagnostic-support.htm" target="_blank">Veterinary Investigation Centre</a>.</p>

		    <p class="push-double--bottom push--top">If you require assistance with the web application or experience technical problems, call our IT support team on {{{Config::get('ahvla.it-support-phone-number')}}} or email <a href="mailto:{{{Config::get('ahvla.it-support-email')}}}">{{{Config::get('ahvla.it-support-email')}}}</a>.  The Helpdesk is open between 9am – 5pm Monday to Friday.</p>
		 
			<p class="push-double--bottom soft--bottom"><a href="https://www.gov.uk/call-charges" target="_blank">Find out more about call charges</a>.</p>

            <a href="javascript:history.back()" class="no-js-hide">Back to previous page</a>
    		<p>{{ link_to_route('landing', 'Go to homepage') }}</p>

	    </div>
	</div>
    
@stop