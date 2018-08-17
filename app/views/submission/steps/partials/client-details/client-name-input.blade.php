@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'edited_client_name']
)

@section('before_validation_box')
@overwrite

@section('validation_box')
<div class="row validation-group">
    <label for="edited_client_name">Name:</label>
    {{Form::text(
        'edited_client_name',
        $persistence->edited_client_name,
        ['id'=>'edited_client_name', 'class'=>'persistentInput form-control','autocomplete' => 'off']
    )}}
</div>
@overwrite

@section('after_validation_box')
@overwrite