@extends('layouts.master')
@section('title', 'Add to Lookup Table')
@section('content')

    <h1 class="heading-large">Create {{{ $table->description }}} Lookup Table Values</h1>

    {{ Form::open(['name'=>'create_form', 'class'=>'form', 'autocomplete'=>'off']) }}

    {{ Form::hidden('tableId', $table->id) }}
    {{ Form::hidden('table', $table->table_name) }}

    <fieldset>
        <legend class="visuallyhidden">Add to lookup table</legend>

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

        <table>
            <thead>
            <tr>
                @foreach ($cols as $col)
                    <td>
                        {{{ $col }}}
                    </td>
                @endforeach
            </tr>
            </thead>
            <tbody>

            {{-- Create 10 blank rows --}}
            @for ($rowNum = 1; $rowNum<=10; $rowNum++)
                <tr>

                    @define($colNum=-1)

                    {{-- Iterate each COLUMN --}}
                    @foreach ($cols as $col)
                        @define($colNum++)
                        @if ('id' === $cols[$colNum])
                            <td>n/a</td>
                        @else
                        <td>

                            @define( $fieldName = $cols[$colNum].'['.$rowNum.'][updated]' )
                            @define( $fieldNameDot = $cols[$colNum].'.'.$rowNum.'.updated' )

                            <div @if (!$errors->first($fieldNameDot)) class="row" @else class="row validation" @endif>
                                @if ($errors->first($fieldNameDot))
                                    <span class="validation-message">{{{$errors->first($fieldNameDot)}}}</span>
                                @endif
                                {{ Form::text($fieldName,  '', array('id' => $fieldNameDot, 'class' => 'form-control','autocomplete' => 'off')) }}
                            </div>
                        </td>
                        @endif
                    @endforeach

                </tr>
            @endfor

            </tbody>
        </table>

        <div class="row push--top">
            {{Form::submit('Save changes',['class'=>'button'],['name'=>'edit_button'])}}
            <p>{{ link_to_route('crud.crud', 'Cancel') }}</p>
        </div>

    </fieldset>

    {{Form::close()}}

@stop