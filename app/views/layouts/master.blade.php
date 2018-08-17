<!DOCTYPE html>
<!--[if lt IE 9]><html class="lte-ie8" lang="en"><![endif]--><!--[if gt IE 8]><!--><html lang="en">
<!--<![endif]--><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
    {{--via grunt--}}

    {{HTML::style('assets/stylesheets/govuk-template-'.Config::get('app.version').'.css')}}
    <!--[if gt IE 8]>{{HTML::style('assets/stylesheets/govuk-template-'.Config::get('app.version').'.css')}}<![endif]-->
    <!--[if IE 6]>{{HTML::style('assets/stylesheets/govuk-template-ie6-'.Config::get('app.version').'.css')}}<![endif]-->
    <!--[if IE 7]>{{HTML::style('assets/stylesheets/govuk-template-ie7-'.Config::get('app.version').'.css')}}<![endif]-->
    <!--[if IE 8]>{{HTML::style('assets/stylesheets/govuk-template-ie8-'.Config::get('app.version').'.css')}}<![endif]-->
    <!--<![endif]--><!--[if lt IE 9]>
      <script src="https://assets.digital.cabinet-office.gov.uk/static/ie-fc5bd25c5f46587b9bff917417ab2b7f.js" type="text/javascript"></script>
    <![endif]-->
    {{HTML::style('assets/stylesheets/govuk-template-print-'.Config::get('app.version').'.css', array('media' => 'print'))}}

    {{HTML::style('assets/stylesheets/fonts-'.Config::get('app.version').'.css')}}

    {{HTML::style('assets/stylesheets/application-'.Config::get('app.version').'.css')}}

    <!--[if gt IE 8]>{{HTML::style('assets/stylesheets/application-'.Config::get('app.version').'.css')}}<![endif]-->
    <!--[if IE 6]>{{HTML::style('assets/stylesheets/application-ie6-'.Config::get('app.version').'.css')}}<![endif]-->
    <!--[if IE 7]>{{HTML::style('assets/stylesheets/application-ie7-'.Config::get('app.version').'.css')}}<![endif]-->
    <!--[if IE 8]>{{HTML::style('assets/stylesheets/application-ie8-'.Config::get('app.version').'.css')}}<![endif]-->
    <!--[if IE 9]>{{HTML::style('assets/stylesheets/application-ie9-'.Config::get('app.version').'.css')}}<![endif]-->

    {{HTML::style('assets/stylesheets/font-awesome.min.css')}}

    {{HTML::script('assets/javascripts/jquery.min.js')}}
    {{HTML::script('assets/javascripts/main-'.Config::get('app.version').'.js')}}
    {{HTML::script('assets/javascripts/plugins-'.Config::get('app.version').'.js')}}
    {{HTML::script('https://code.jquery.com/ui/1.11.4/jquery-ui.min.js')}}

    <!--[if lt IE 9]>{{HTML::script('assets/javascripts/ie.js')}}<![endif]-->
    @section('head')
    @show
</head>

<body data-spy="scroll" data-target="#nav-questions" class="no-js">
<script type="text/javascript">document.body.className = ((document.body.className) ? document.body.className + ' js-enabled' : 'js-enabled');</script>
@if(isset($gacode)&&(!empty($gacode)))
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', '{{{$gacode}}}', 'auto');
ga('send', 'pageview');
</script>
@endif

<div id="global-cookie-message">
    <div class="message__wrapper">
        <p>
            GOV.UK uses cookies to make the site simpler. <a href="https://www.gov.uk/help/cookies">Find out more about cookies</a>
        </p>
        <p><a href="#global-cookie-message" id="global-cookie-message-button">Close</a></p>
    </div>
</div>

@section('header')
    @include('layouts.header')
@show

@section('header-bar')
    @include('layouts.header-bar')
@show

<main id="content" class="">

    @section('phase-banner')
        @include('layouts.phase-banner')
    @show

    @if(isset($loggedUser))
    <div class="grid-row">
        @section('account-management')
            @include('layouts.account-management')
        @show
    </div>
    @endif

    @yield('content')

</main>

{{{ versionRelease() }}}

    @section('footer')
        @include('layouts.footer')
    @show

</body>
</html>
