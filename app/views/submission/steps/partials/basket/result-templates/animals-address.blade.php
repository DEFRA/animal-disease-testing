@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'animals_at_address']
)

@section('before_validation_box')
@overwrite

@section('validation_box')

    <fieldset class="inline">
        <legend>Animals address</legend>
        <p>Has the animals address changed since your first paired submission?</p>
        <label class="block-label">
            <input id="animals_at_address_0" type="radio" class="persistentInput" name="animals_at_address"
                   value="0" <?php if ($persistence->animals_at_address === '0') echo 'checked'; ?>>&nbsp;Yes
        </label>
        <label class="block-label">
            <input id="animals_at_address_1" type="radio" class="persistentInput" name="animals_at_address"
                   value="1" <?php if ($persistence->animals_at_address === '1') echo 'checked'; ?>>&nbsp;No
        </label>
    </fieldset>

@overwrite

@section('after_validation_box')
@overwrite