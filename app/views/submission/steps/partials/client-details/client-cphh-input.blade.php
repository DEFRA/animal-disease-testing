@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'edited_client_cphh']
)

@section('before_validation_box')
@overwrite

@section('validation_box')
<div class="row validation-group">
    <label for="edited_client_cphh"><abbr title="County Parish Holding Herd">CPH </abbr>number eg 48/234/2348:</label>
    {{Form::text(
        'edited_client_cphh',
        $persistence->edited_client_cphh,
        ['id'=>'edited_client_cphh', 'class'=>'persistentInput form-control js-cphh','autocomplete' => 'off']
    )}}
</div>
@overwrite

@section('after_validation_box')

@overwrite