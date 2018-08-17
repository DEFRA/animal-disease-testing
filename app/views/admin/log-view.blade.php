@extends('layouts.master')
@section('title', 'System logs -')
@section('content')

    <h1 class="heading-large">System logs</h1>

    <h2>API request logs</h2>
    <table>
        <tr>
            <th>Date</th>
            <td></td>
        </tr>
        @foreach($apiRequestLogs as $log)
            <tr>
                <td>{{{$log->getName()}}}</td>
                <td>
                    {{ link_to_route('logs.download', 'Download', ['type' => 'api', 'filename' => $log->getFileName()]) }}
                </td>
            </tr>
        @endforeach
    </table>

    <h3>Error logs</h3>
    <table>
        <tr>
            <th>Date</th>
            <td></td>
        </tr>
        @foreach($errorLogs as $log)
            <tr>
                <td>{{{$log->getName()}}}</td>
                <td>
                    {{ link_to_route('logs.download', 'Download', ['type' => 'error', 'filename' => $log->getFileName()]) }}
                </td>
            </tr>
        @endforeach
    </table>

    <h3>Find an error</h3>

    {{Form::open(['name' => 'find_error', 'url' => URL::route('logs.find').'#find-an-error', 'class' => 'form', 'autocomplete' => 'off', 'id' => 'find-an-error'])}}
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

        <div @if (!$errors->first('date')) class="row" @else class="row validation" @endif>
            {{Form::label('date','Date of error')}}
            @if ($errors->first('date'))
                <span class="validation-message">{{{$errors->first('date')}}}</span>
            @endif
            {{ Form::select('date', $errorDates, array('class' => 'form-control','autocomplete' => 'off')) }}
        </div>

        <div @if (!$errors->first('error_code')) class="row" @else class="row validation" @endif>
            {{Form::label('error_code','Error  code')}}
            @if ($errors->first('error_code'))
                <span class="validation-message">{{{$errors->first('error_code')}}}</span>
            @endif
            {{ Form::text('error_code', '', array('class' => 'form-control', 'autocomplete' => 'off')) }}
        </div>

        <div class="row push--top">
            {{Form::submit('Find error', ['class' => 'button'], ['name' => 'edit_button'])}}
        </div>

    {{Form::close()}}

    @if ($errorData)
        <p>
            {{{nl2br($errorData)}}}
        </p>
    @endif

@stop