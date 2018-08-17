@extends('layouts.master')
@section('title', 'Sign In -')
@section('content')

<h1 class="heading-large">Sign in</h1>

<section class="login__form login--half">

    @if (! $disableLogin)
    <p>Sign in to get started.</p>

    @if(Session::has('session-timeout'))
        <div class="alert-box success">
            <h2>{{{ Session::get('session-timeout') }}}</h2>
        </div>
    @endif

    {{ Form::open(array('name'=>'login_form','class'=>'form','autocomplete'=>'off'))  }}
    <fieldset>
        <legend class="visuallyhidden">Sign in</legend>

    @if ($errors->count())

        <div class="validation-summary group" role="alert">
            <h3 class="error-heading">There was a problem submitting the form</h3>
            <p>Because of the following problems:</p>

            <ul class="error-list">
            @foreach ($errors->getMessageBag()->getMessages() as $id => $messageArr)
                <li>
                    @if ($id == 'login_result')
                        {{$messageArr[0]}}
                    @else
                        <a href="#{{$id}}">{{$messageArr[0]}}</a>
                    @endif
                </li>
            @endforeach
            </ul>
        </div>

    @endif

        <div @if (!$errors->first('email')) class="row" @else class="row validation" @endif>
            {{Form::label('email','Email:')}}
            @if ($errors->first('email'))
                <span class="validation-message">{{{$errors->first('email')}}}</span>
            @endif
            {{ Form::email('email', '', array('class' => 'form-control','autocomplete'=>'off')) }}
        </div>

        <div @if (!$errors->first('password')) class="row" @else class="row validation" @endif>
            {{Form::label('password','Password:')}}
            @if ($errors->first('password'))
                <span class="validation-message">{{$errors->first('password')}}</span>
            @endif
            {{ Form::password('password', array('class' => 'form-control','autocomplete'=>'off')) }}
        </div>

        <div class="row">
            {{Form::submit('Sign in',['id'=>'login-submit','class'=>'button push--top'],['name'=>'login_button'])}}
            <p>{{ link_to_route('request-reset-password-form', 'Forgotten password?', null, array('id' => 'forgotten-password') ) }}</p>
            <p><a href="/register">Not yet registered? (Register your practice).</a></p>
        </div>

    </fieldset>

    {{Form::close()}}

</section>

<aside class="login--half">
    <div class="notice">
        <i class="icon icon-time">
            <span class="visuallyhidden">Warning</span>
        </i>
        <strong class="bold-small">
            Please note: scheduled service downtime is 10pm - 2am daily and 4am - 6am each Wednesday.
        </strong>
    </div>

        @endif

        @if(isset($apiAlert) && $apiAlert)
            <?php
            $divClass = $apiAlert->type != 'error' ? 'info' : '';
            ?>
            <div class="validation-summary group {{{$divClass}}}" role="alert">
                <h3 class="error-heading">{{{ $apiAlert->title }}}</h3>
                <p>{{{ $apiAlert->content }}}</p>
            </div>

        </section>
        @endif

    <section class="login__message {{{ $disableLogin ? 'login--disabled' : '' }}} ">
        @if (isset($alert) && $alert)
            <?php
            $divClass = $alert->type != 'error' ? 'info' : '';
            ?>
            <div class="validation-summary group {{{$divClass}}}" role="alert">
                <h3 class="error-heading">{{{ $alert->title }}}</h3>
                <p>{{{ $alert->content }}}</p>
            </div>
        @endif
    </section>
</aside>
@stop