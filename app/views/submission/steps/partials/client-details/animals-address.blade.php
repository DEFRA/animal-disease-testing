@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'animals_at_address']
)

@section('before_validation_box')
@overwrite

@section('validation_box')

    <fieldset class="inline">
        <legend class="visuallyhidden">Animals address</legend>
        <p>Are the animals at the client’s address? <span class="js-hidden">If yes continue to next step.</span></p>
        <label class="block-label">
            <input id="animals_at_address_1" type="radio" class="persistentInput" name="animals_at_address"
                   value="1" <?php if ($persistence->animals_at_address == '1') echo 'checked'; ?>>&nbsp;Yes
        </label>
        <label class="block-label">
            <input id="animals_at_address_0" type="radio" class="persistentInput" name="animals_at_address"
                   value="0" <?php if ($persistence->animals_at_address == '0') echo 'checked'; ?>>&nbsp;No
        </label>
    </fieldset>
    <hr />

@overwrite

@section('after_validation_box')
@overwrite