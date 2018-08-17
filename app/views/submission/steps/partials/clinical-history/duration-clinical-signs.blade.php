<fieldset class="inline">
    <legend>Duration of clinical signs (optional)</legend>

    <?php
        if ( is_object($persistence->clinical_signs) ) {
            $value = $persistence->clinical_signs->getLimsCode();
        }
        else {
            $value = $persistence->clinical_signs;
        }
    ?>

    <label class="block-label" data-target="example-ni-number" for="duration-clinical-signs-1">
        {{Form::radio('clinical_signs','3D',($value=='3D'),['id'=>'duration-clinical-signs-1','class'=>'persistentInput'])}}
        {{{\ahvla\entity\clinicalSign\ClinicalSignDuration::getOptions()['3D']}}}
    </label>

    <label class="block-label" for="duration-clinical-signs-2">
        {{Form::radio('clinical_signs','LT2WKS',($value=='LT2WKS'),['id'=>'duration-clinical-signs-2','class'=>'persistentInput'])}}
        {{{\ahvla\entity\clinicalSign\ClinicalSignDuration::getOptions()['LT2WKS']}}}
    </label>

    <label class="block-label" for="duration-clinical-signs-3">
        {{Form::radio('clinical_signs','GT2WKS',($value=='GT2WKS'),['id'=>'duration-clinical-signs-3','class'=>'persistentInput'])}}
        {{{\ahvla\entity\clinicalSign\ClinicalSignDuration::getOptions()['GT2WKS']}}}
    </label>

    <label class="block-label" for="duration-clinical-signs-4">
        {{Form::radio('clinical_signs','UNKNOWN',($value=='UNKNOWN'),['id'=>'duration-clinical-signs-4','class'=>'persistentInput'])}}
        {{{\ahvla\entity\clinicalSign\ClinicalSignDuration::getOptions()['UNKNOWN']}}}
    </label>
</fieldset>
<hr />