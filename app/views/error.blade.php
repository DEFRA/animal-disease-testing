@extends('layouts.master')
@section('title', 'Error -')
@section('content')

    <h1>Sorry something went wrong. {{{ $code }}}</h1>

    </br>

    <p>
        Please call our IT support team on {{{Config::get('ahvla.it-support-phone-number')}}} or email <a href="mailto:{{{Config::get('ahvla.it-support-email')}}}">{{{Config::get('ahvla.it-support-email')}}}</a>.  The Helpdesk is open between 9am – 5pm Monday to Friday.
    </p>

    <p>
        Quote the error reference and the full web address in any communication.
    </p>
@stop
