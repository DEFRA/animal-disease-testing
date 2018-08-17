@extends('layouts.master')
@section('title', 'Delete Practice - Practice Management -')
@section('content')

<h1 class="heading-large">Delete practice</h1>

{{Form::open(['name'=>'delete_practice_form', 'route'=> ['practice.delete', $practice->getId()], 'class'=>'form', 'autocomplete'=>'off'])}}
<fieldset>
    <legend class="visuallyhidden">Delete practice</legend>

    {{Form::hidden('practice_id', $practice->getId())}}

    <p>Confirm that you want to delete {{{ $practice->getName() }}}&hellip;</p>

    <div class="row push--top">
        {{Form::submit('Confirm delete',['class'=>'button button-warning'],['name'=>'delete_button'])}}
        <p>{{ link_to(URL::previous(), 'Cancel') }}</p>
    </div>
    
</fieldset>
{{Form::close()}}

@stop