@extends('layouts.master')
@section('title', 'Edit Lookup Table')
@section('content')

    <h1 class="heading-large">Edit {{{ $table->description }}} Lookup Table</h1>

    <div>
        {{ link_to_route('crud.create-crud', 'Add new fields', ['tableId'=>$table->id], ['class'=>'button']) }}
    </div>

    {{ Form::open(['name'=>'edit_lookup_form', 'class'=>'form', 'autocomplete'=>'off']) }}
    {{ Form::hidden('tableId', $table->id) }}
    {{ Form::hidden('table', $table->table_name) }}
    <fieldset>
        <legend class="visuallyhidden">Edit lookup table</legend>
<?php
//        dd($errors);
        ?>
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
                <td>
                    Delete
                </td>
            </tr>
            </thead>
            <tbody>

            @define($rowNum=-1)

            {{-- Iterate each ROW --}}
            @foreach ($data as $rowFields)

                <tr>

                @define($rowNum++)
                @define($colNum=-1)

                {{-- Iterate each COLUMN --}}
                @foreach ($rowFields as $rowField)
                    @define($colNum++)

                    <td>
                    @if ('id' === $cols[$colNum])
                        {{{ $rowField }}}
                    @else

                        @define( $fieldNameOriginal = $cols[$colNum].'['.$data[$rowNum]->id.'][original]' )
                        @define( $fieldName = $cols[$colNum].'['.$data[$rowNum]->id.'][updated]' )
                        @define( $fieldNameDot = $cols[$colNum].'.'.$data[$rowNum]->id.'.updated' )

                        <div @if (!$errors->first($fieldNameDot)) class="row" @else class="row validation" @endif>
                        @if ($errors->first($fieldNameDot))
                        <span class="validation-message">{{{$errors->first($fieldNameDot)}}}</span>
                        @endif
                        {{ Form::text  ($fieldName,  $rowField, array('id' => $fieldNameDot, 'class' => 'form-control','autocomplete' => 'off')) }}
                        {{ Form::hidden($fieldNameOriginal, $rowField) }}
                        </div>
                    @endif
                    </td>
                @endforeach
                    <td>

                        <div class="row push--top">
                            <p>{{ link_to_route('crud.delete', 'Delete', [ 'tableId' => $table->id, 'fieldId' => $data[$rowNum]->id ], ['class' => 'button button-warning']) }}</p>
                        </div>

                    </td>

                </tr>
            @endforeach

            </tbody>
        </table>

        <ul class="previous-next-navigation">
            <li id="page-left-nav" class="previous">
                @if ( null !== $previousPage && $previousPage !== 0 )
                    <a page="{{ $previousPage }}" class="test_search_input_link hide-link" id="previous-page" next_page="{{ $currentPage-1 }}" href="/crud/{{ $table->id }}/edit?page={{ $currentPage-1 }}{{{ $filters }}}">Previous <span class="visuallyhidden">page</span>
                        <span class="page-numbers">{{{ $currentPage-1 }}} of {{{ $totalPages }}}</span>
                    </a>
                @endif
            </li>
            <li id="page-right-nav" class="next">
                @if ( null !== $nextPage && $nextPage-1 !== 0 && $nextPage <= $totalPages )
                    <a page="{{ $nextPage }}" class="test_search_input_link hide-link" id="next-page" next_page="{{ $currentPage+1 }}"  href="/crud/{{ $table->id }}/edit?page={{ $currentPage+1 }}{{{ $filters }}}">Next <span class="visuallyhidden">page</span>
                        <span class="page-numbers">{{{ $currentPage+1 }}} of {{{ $totalPages }}}</span></a>
                @endif
            </li>
        </ul>
        
        <div class="row push--top">
            {{Form::submit('Save changes',['class'=>'button'],['name'=>'edit_button'])}}
            <p>{{ link_to_route('crud.crud', 'Cancel') }}</p>
        </div>

    </fieldset>

    {{Form::close()}}

@stop