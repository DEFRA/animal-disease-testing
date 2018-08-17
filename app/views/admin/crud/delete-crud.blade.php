@extends('layouts.master')
@section('title', 'Delete Lookup Table Item - Database Management -')
@section('content')

<h1 class="heading-large">Delete lookup table item</h1>

{{Form::open(['name'=>'create_lookup_form', 'method'=>'delete', 'route'=>['crud.delete', $tableId, $fieldId], 'class'=>'form', 'autocomplete'=>'off'])}}
<fieldset>
    <legend class="visuallyhidden">Delete lookup table value</legend>

    {{ Form::hidden('tableId', $tableId) }}

    <p>Confirm that you want to delete the record from table <strong>{{{ $tableName }}}</strong> &hellip;</p>
    <p>{{{ $data }}}</p>

    <div class="row push--top">
        {{Form::submit('Confirm delete',['class'=>'button button-warning'],['name'=>'delete_button'])}}
        <p>{{ link_to(URL::previous(), 'Cancel') }}</p>
    </div>
    
</fieldset>
{{Form::close()}}

@stop