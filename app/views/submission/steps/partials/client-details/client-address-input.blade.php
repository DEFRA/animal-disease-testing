@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'edited_client_address_line1']
)

@section('before_validation_box')
@overwrite

@section('validation_box')
<div class="row validation-group">

    <label for="edited_client_address_line1">Farm name:</label>
    {{Form::text(
        'edited_client_address_line1',
        $persistence->edited_client_address_line1,
        ['id'=>'edited_client_address_line1','class'=>'persistentInput form-control push--bottom','autocomplete' => 'off']
    )}}

    <label for="edited_client_address_line2">Address:</label>
    {{Form::text(
        'edited_client_address_line2',
        $persistence->edited_client_address_line2,
        ['id'=>'edited_client_address_line2','class'=>'persistentInput form-control push--bottom','autocomplete' => 'off']
    )}}
    <label for="edited_client_address_line3" class="visuallyhidden">Address 3:</label>
    {{Form::text(
        'edited_client_address_line3',
        $persistence->edited_client_address_line3,
        ['id'=>'edited_client_address_line3','class'=>'persistentInput form-control push--bottom','autocomplete' => 'off']
    )}}
    <label for="edited_client_address_line4" class="visuallyhidden">Address 4:</label>
    {{Form::text(
        'edited_client_address_line4',
        $persistence->edited_client_address_line4,
        ['id'=>'edited_client_address_line4','class'=>'persistentInput form-control push--bottom','autocomplete' => 'off']
    )}}

    <label for="edited_client_address_line6">County:</label>

    {{Form::select(
    'edited_client_address_line6',
    $select_counties_elements,
    $persistence->edited_client_address_line6,
    ['id'=>'edited_client_address_line6','class'=>'persistentInput form-control push--bottom','autocomplete' => 'off'],
    ''
    )}}

    <label for="edited_client_address_line7">Postcode:</label>
    {{Form::text(
        'edited_client_address_line7',
        $persistence->edited_client_address_line7,
        ['id'=>'edited_client_address_line7','class'=>'persistentInput form-control push--bottom','autocomplete' => 'off']
    )}}
</div>
@overwrite

@section('after_validation_box')
@overwrite