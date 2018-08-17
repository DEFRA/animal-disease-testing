<span @if(isset($hiddenReference))style="display: none" @endif @if(isset($packageId)) data-id="{{{'removeAnimalId_'.(isset($packageId)?$packageId:'').'_'.(isset($animalIdSample)?$animalIdSample->animal->id:'')}}}" @endif class="product_animal_sample_id">
    <div class="sample-id">
        @if(isset($readOnlyMode))
            {{{(isset($animalIdSample)?$animalIdSample->sampleId:'')}}}
        @else
            @if ($animalIdSample->isSOP)
                <?php $sop = '_SOP'; ?>
                <?php $sopLabel = 'second of pair'; ?>
            @else
                <?php $sop = ''; ?>
                <?php $sopLabel = ''; ?>
            @endif
            @if(isset($packageId))
                <?php $sampleId = 'sampleid_package_'.$packageId.'_'; ?>
                <?php $sampleLabel =  'Sample '.(isset($animalIdSample)?$animalIdSample->animal->description:'').' for test '.(isset($basketProduct)?$basketProduct->id:'').' in package '.$packageId.' '.$sopLabel; ?>
            @else
                <?php $sampleId = 'sampleid_'; ?>
                <?php $sampleLabel =  'Sample '.(isset($animalIdSample)?$animalIdSample->animal->description:'').' for test '.(isset($basketProduct)?$basketProduct->id:'').' '.$sopLabel; ?>
            @endif
            <?php $sampleName =  $sampleId.(isset($animalIdSample)?$animalIdSample->animal->id:'').'_'.(isset($basketProduct)?$basketProduct->id:'').$sop; ?>
            <label for="{{{$sampleName}}}" class="visuallyhidden">{{{$sampleLabel}}}</label>
            {{Form::text(
                $sampleName,
                (isset($animalIdSample)?$animalIdSample->sampleId:''),
                ['id'=>$sampleName,'class'=>'persistentInput sampleId form-control','autocomplete' => 'off'])}}
        @endif
    </div>
    <div class="quantity">
        <p>
            <span class="JSON_animalId">{{{isset($animalIdSample)?$animalIdSample->animal->description:''}}}</span>
        </p>
    </div>
    @if(!isset($packageId))
        <div class="pool">
            <?php $poolGroupName = 'poolGroupId_'.(isset($animalIdSample)?$animalIdSample->animal->id:'').'_'.(isset($basketProduct)?$basketProduct->id:''); ?>
            <label for="{{{$poolGroupName}}}" class="visuallyhidden">Pool group {{{(isset($poolGroup)?$poolGroup:'')}}} for test {{{$basketProduct->id}}}</label>
            {{Form::text(
                    $poolGroupName,
                    (isset($poolGroup)?$poolGroup:''),
                    ['id'=>$poolGroupName,'class'=>'persistentInput poolId form-control poolActive'.((isset($disabled) && $disabled)?' non-pooled-sample':''),'autocomplete' => 'off',((isset($disabled) && $disabled)?'disabled':'')])}}
        </div>
        <div class="price-col">
            @if(isset($disabled) && !$disabled)
                <p></p>
            @else
                <p>&pound;<span class="JSON_price">{{{isset($basketProduct)?number_format(round($basketProduct->price,2),2):''}}}</span></p>
            @endif
        </div>
        <div class="remove-animal">
            @if(!isset($readOnlyMode) && (isset($hiddenReference) || $animalIdSampleCount > 1))
                <?php
                $name = 'removeAnimalId_' . ( isset($basketProduct) ? $basketProduct->id : '' ) . '_' . ( isset($animalIdSample) ? $animalIdSample->animal->id : '' );
                $id = 'removeAnimalId_' . ( isset($basketProduct) ? $basketProduct->id : '' ) . '_' . ( isset($animalIdSample) ? $animalIdSample->animal->id : '' );
                ?>
                <a class="removeAnimalId" id={{$id}} href="{{ URL::route('removeAnimal', array('productId' => $basketProduct->id,'animalId' => $animalIdSample->animal->id,'draftSubmissionId' => $fullSubmissionForm->draftSubmissionId)) }}"><span id="label_{{$id}}">Remove</span><span class="visuallyhidden"> animal ID {{$animalIdSample->animal->description}} for {{$basketProduct->id}}</span></a>
            @endif
        </div>
    @endif
</span>
<div class="clear"></div>