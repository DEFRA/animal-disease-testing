@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'clinical_signs_list']
)

@section('before_validation_box')
@overwrite

@section('validation_box')
    <fieldset>
        <legend>Select clinical signs in order of importance</legend>

        <p class="no-js-hide">A maximum of 3 clinical signs can be selected.</p>
        <p class="js-hide">Enter numeric values below, 1 representing the most important. A maximum of 3 clinical signs can be entered.</p>

        <div id="validationGlobalDiv" class="clinical-signs__validation">
        </div>

        <?php

        for($idx=0,$idxMax=count($clinical_signs);$idx<$idxMax;$idx+=3) {

            if (isset($clinical_signs[$idx])) {
                $validationHookInputAttribute = '';
                if($idx === 0){
                    $validationHookInputAttribute = ' data-validation-name="clinical_signs_list"';
                }
                $tagName = 'clinical_signs_'.$clinical_signs[$idx]->lims_code;
                $value1 = empty( $persistence->$tagName ) ? '':$persistence->$tagName;
                $value1 = e($value1);
                echo '<div class="grid-row clinical-signs"><div class="column-third">';
                echo '<span class="no-js-hide js-sign-select"></span>';
                echo '<label class="clinical-signs__label">';
                echo '<input id="clinical-signs-'.$idx.'" autocomplete="off" maxlength="1" type="text" name="'.$tagName.'" value="'.$value1.'" class="clinical-signs__input input-2digits-wide text--center js-numeric" '.$validationHookInputAttribute.'>';
                echo ''.$clinical_signs[$idx]->description;
                echo '</label>';
                echo '</div>';
            }

            if (isset($clinical_signs[$idx+1])) {
                $tagName = 'clinical_signs_'.$clinical_signs[$idx+1]->lims_code;
                $value1 = empty( $persistence->$tagName ) ? '':$persistence->$tagName;
                $value1 = e($value1);
                echo '<div class="column-third">';
                echo '<span class="no-js-hide js-sign-select"></span>';
                echo '<label class="clinical-signs__label">';
                echo '<input id="clinical-signs-'.$idx.'" autocomplete="off" maxlength="1" type="text" name="'.$tagName.'" value="'.$value1.'" class="clinical-signs__input input-2digits-wide text--center js-numeric">';
                echo ''.$clinical_signs[$idx+1]->description;
                echo '</label>';
                echo '</div>';
            }


            if (isset($clinical_signs[$idx+2])) {
                $tagName = 'clinical_signs_'.$clinical_signs[$idx+2]->lims_code;
                $value1 = empty( $persistence->$tagName ) ? '':$persistence->$tagName;
                $value1 = e($value1);
                echo '<div class="column-third">';
                echo '<span class="no-js-hide js-sign-select"></span>';
                echo '<label class="clinical-signs__label">';
                echo '<input id="clinical-signs-'.$idx.'" autocomplete="off" maxlength="1" type="text" name="'.$tagName.'" value="'.$value1.'" class="clinical-signs__input input-2digits-wide text--center js-numeric">';
                echo ''.$clinical_signs[$idx+2]->description;
                echo '</label>';
                echo '</div>';
            }

            echo '</div>';
        }

        if (!$clinical_signs) {
            echo 'No clinical signs, select a species in animal details step.';
        }

        ?>

    </fieldset>
    <hr />
@overwrite

@section('after_validation_box')
@overwrite