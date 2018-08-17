@extends('layouts.master')
@section('title', 'Edit information message -')
@section('content')

<h1 class="heading-large">Edit information message</h1>

{{ Form::model($message, array('route' => array('information-messages.update', $message->id))) }}
<fieldset>
    <legend class="visuallyhidden">Edit information message</legend>

    @if ($errors->count())
        <div class="validation-summary group" role="alert">
            <h2 class="error-heading">There was a problem submitting the form</h2>
            <p>Because of the following problems:</p>

            <ul class="error-list">
            @foreach ($errors->getMessageBag()->getMessages() as $id => $messageArr)
                <li>
                    <a href="#{{{$id}}}">{{{$messageArr[0]}}}</a>
                </li>
            @endforeach
            </ul>
        </div>
    @endif

    <div @if (!$errors->first('title')) class="row" @else class="row validation" @endif>
        {{Form::label('title', 'Title')}}
        @if ($errors->first('title'))
            <span class="validation-message">{{{$errors->first('title')}}}</span>
        @endif
        {{ Form::text('title', null, array('class' => 'form-control','autocomplete' => 'off')) }}
    </div>    

    <div @if (!$errors->first('content')) class="row" @else class="row validation" @endif>
        {{Form::label('messageContent', 'Content')}}
        @if ($errors->first('content'))
            <span class="validation-message">{{{$errors->first('content')}}}</span>
        @endif
        {{ Form::textarea('content', null, array('rows' => 5, 'class' => 'form-control', 'id' => 'messageContent', 'autocomplete' => 'off')) }}
    </div>

    <div class="row">
        <p>What type of message is this?</p>
        <div class="inline">
            <label for="type_info" class="block-label">
                {{ Form::radio('type', "info", null, ["id" => "type_info"]) }}&nbsp;Information
            </label>
            <label for="type_error" class="block-label">
                {{ Form::radio('type', "error", null, ["id" => "type_error"]) }}&nbsp;Error
            </label>
        </div>
    </div>

    <div class="row push--top">
        {{Form::submit('Save changes',['class'=>'button'],['name'=>'edit_button'])}}
        <p>{{ link_to_route('home', 'Cancel') }}</p>
    </div>    
</fieldset>
{{ Form::close() }}

@stop