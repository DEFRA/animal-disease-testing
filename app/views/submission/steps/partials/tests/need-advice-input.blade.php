@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'need_advice']
)

@section('before_validation_box')
@overwrite

@section('validation_box')
<fieldset class="inline">
    <legend>Do you need advice choosing tests?</legend>
    <p>Using clinical signs and species you will be able to see a list of tests that are recommended by our Species Experts.</p>
    <div class="row">
        <?php
        $radioGroupData = [
                'name' => 'need_advice',
                'radios' => [0 => 'No', 1 => 'Yes'],
                'checked' => $persistence->need_advice,
                'refresh_button' => true
        ];
        ?>
        @include('submission.inputs.radiogroup', $radioGroupData)
    </div>
    <div class="panel-indent flush--bottom js-need-advice" @if(!$persistence->need_advice)style="display:none" @endif>
        <p>Please note that test advice is for farmed livestock (cattle, small ruminants, pigs) and farmed birds only.</p>
    </div>
</fieldset>
<hr/>
@overwrite


@section('after_validation_box')
@overwrite