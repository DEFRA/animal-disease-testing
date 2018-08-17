<!DOCTYPE html>
<!--[if lt IE 9]><html class="lte-ie8" lang="en"><![endif]--><!--[if gt IE 8]><!--><html class="report-html" lang="en">
<!--<![endif]--><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">

<title>Welcome to GOV.UK</title>
    {{--via grunt--}}
    <!--[if IE 6]>{{HTML::style('assets/stylesheets/govuk-template-ie6.css')}}<![endif]-->
    {{HTML::style('assets/stylesheets/main.css')}}
    {{HTML::style('assets/stylesheets/elements-page.css')}}
    {{HTML::style('assets/stylesheets/govuk-template.css')}}

    {{HTML::style('assets/stylesheets/application.css')}}

	{{HTML::script('assets/javascripts/jquery.min.js')}}
	{{HTML::script('assets/javascripts/main.js')}}

    @section('head')
    @show
</head>

<body class="report-body" data-spy="scroll" data-target="#nav-questions">

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

<div class="report-global">
@yield('content')
</div>

</body>
</html>
