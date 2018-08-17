@extends('layouts.master')
@section('title', 'Register -')
@section('content')

<h1 class="heading-large">Account registration</h1>

<p>Register an account to get started.</p>

<hr />

<section class="register__form register--half">

    @if(Session::has('session-timeout'))
    <div class="alert-box success">
        <h2>{{{ Session::get('session-timeout') }}}</h2>
    </div>
    @endif

    {{ Form::open(array('name'=>'register_form','class'=>'form','autocomplete'=>'off'))  }}
    <fieldset>
        <legend class="visuallyhidden">Register</legend>

        @if ($errors->count())

        <div class="validation-summary group" role="alert">
            <h3 class="error-heading">There was a problem submitting the form</h3>
            <p>Because of the following problems:</p>

            <ul class="error-list">
                @foreach ($errors->getMessageBag()->getMessages() as $id => $messageArr)
                <li>
                    @if ($id == 'register_result')
                    {{{$messageArr[0]}}}
                    @else
                    <a href="#{{{$id}}}">{{{$messageArr[0]}}}</a>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>

        @endif

        <fieldset class="inline">
            <legend>Are you an existing APHA laboratory testing customer?</legend>
            <div class="row">
                <div class="radioGroup row">

                    <label class="block-label">
                        <input id="value_1" class="persistentInput" name="existing_customer" type="radio" value="1">Yes
                    </label>

                    <label class="block-label">
                        <input id="value_0" class="persistentInput" checked="checked" name="existing_customer" type="radio" value="0">No
                    </label>
                </div>    
            </div>
        </fieldset>


        <h2>Your details</h2>

        <div @if (!$errors->first('business_name')) class="row" @else class="row validation" @endif>
            {{Form::label('business_name','Full name of business:')}}
            @if ($errors->first('business_name'))
            <span class="validation-message">{{{$errors->first('business_name')}}}</span>
            @endif
            {{ Form::text('business_name', '', array('class' => 'form-control','autocomplete'=>'off')) }}
        </div>

        <div @if (!$errors->first('contact_name')) class="row" @else class="row validation" @endif>
            {{Form::label('contact_name','Contact name:')}}
            @if ($errors->first('contact_name'))
            <span class="validation-message">{{{$errors->first('contact_name')}}}</span>
            @endif
            {{ Form::text('contact_name', '', array('class' => 'form-control','autocomplete'=>'off')) }}
        </div>        

        <div @if (!$errors->first('address_1')) class="row" @else class="row validation" @endif>
            {{Form::label('address_1','Address')}}
            @if ($errors->first('address_1'))
            <span class="validation-message">{{{$errors->first('address_1')}}}</span>
            @endif
            {{ Form::text('address_1', '', array('class' => 'form-control','autocomplete'=>'off')) }}
        </div>        

        <div @if (!$errors->first('address_2')) class="row" @else class="row validation" @endif>
            {{--Form::label('address_2','Address 2')--}}
            @if ($errors->first('address_2'))
            <span class="validation-message">{{{$errors->first('address_2')}}}</span>
            @endif
            {{ Form::text('address_2', '', array('class' => 'form-control','autocomplete'=>'off')) }}
        </div> 

        <div @if (!$errors->first('address_3')) class="row" @else class="row validation" @endif>
            {{--Form::label('address_3','Address 3')--}}
            @if ($errors->first('address_3'))
            <span class="validation-message">{{{$errors->first('address_3')}}}</span>
            @endif
            {{ Form::text('address_3', '', array('class' => 'form-control','autocomplete'=>'off')) }}
        </div>     

        <div @if (!$errors->first('county')) class="row" @else class="row validation" @endif>
            {{Form::label('county','County:')}}
            @if ($errors->first('county'))
            <span class="validation-message">{{{$errors->first('county')}}}</span>
            @endif


            {{Form::select(
            'county',
            $select_counties_elements,
            null,
            ['class'=>'persistentInput form-control push--bottom menulist','autocomplete' => 'off'],
            ''
            )}}


        </div>               

        <div @if (!$errors->first('postcode')) class="row" @else class="row validation" @endif>
            {{Form::label('postcode','Postcode:')}}
            @if ($errors->first('postcode'))
            <span class="validation-message">{{{$errors->first('postcode')}}}</span>
            @endif
            {{ Form::text('postcode', '', array('class' => 'form-control','autocomplete'=>'off')) }}
        </div>

        <div @if (!$errors->first('email')) class="row" @else class="row validation" @endif>
            {{Form::label('email','Email:')}}
            @if ($errors->first('email'))
                <span class="validation-message">{{{$errors->first('email')}}}</span>
            @endif
            {{ Form::email('email', '', array('class' => 'form-control','autocomplete'=>'off')) }}
        </div>

        <div @if (!$errors->first('telephone')) class="row" @else class="row validation" @endif>
            {{Form::label('telephone','Telephone:')}}
            @if ($errors->first('telephone'))
            <span class="validation-message">{{{$errors->first('telephone')}}}</span>
            @endif
            {{ Form::text('telephone', '', array('class' => 'form-control','autocomplete'=>'off')) }}
        </div>

        <div class="row">
            {{Form::submit('Submit',['id'=>'register-submit','class'=>'button push--top'],['name'=>'register_button'])}}
        </div>

    </fieldset>

    {{Form::close()}}



    @if(isset($apiAlert) && $apiAlert)
    <?php
    $divClass = $apiAlert->type != 'error' ? 'info' : '';
    ?>
    <div class="validation-summary group {{{$divClass}}}" role="alert">
        <h3 class="error-heading">{{{ $apiAlert->title }}}</h3>
        <p>{{{ $apiAlert->content }}}</p>
    </div>
    @endif
</section>

<section class="register__message register--half">
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

@stop