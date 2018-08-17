<div class="item">
    <h3 class="JSON_name">{{{isset($basketProduct->name)?$basketProduct->name:''}}}</h3>
    <h4>(<span>{{{isset($basketProduct->id)?$basketProduct->id:''}}}</span>)</h4>
    <br />
    <p>
        Maximum turnaround days: <span class="JSON_maxTurnaround">{{{isset($basketProduct)?$basketProduct->maxTurnaround:''}}}</span><br />
        Average turnaround days: <span class="JSON_averageTurnaround">{{{isset($basketProduct)?$basketProduct->averageTurnaround:''}}}</span>
    </p>
    {{--&nbsp;|&nbsp;--}}
    @if(isset($basketProduct) && $basketProduct->getOptions())
        <?php
            $optionTypeLabel = $basketProduct->optionTypeLabel?strtolower($basketProduct->optionTypeLabel):'options';
            $minOptionsLabel = 'Select a minimum of '.$basketProduct->minOptions;
            $maxOptionsLabel = $basketProduct->maxOptions?' and maximum of '.$basketProduct->maxOptions:'';
        ?>
        <p class="flush--bottom"><strong>{{{$minOptionsLabel}}}{{{$maxOptionsLabel}}}&#32;{{{$optionTypeLabel}}}</strong></p>
        <fieldset class="inline">
            @foreach($basketProduct->getOptions() as $option)
                @include('submission.steps.partials.basket.result-templates.sub-templates.product-option-template')
            @endforeach
        </fieldset>
    @endif
    <div class="sample-type">
        @if(!isset($packageId))
            <?php $sampleNameTag = 'sampleTypesSelect_'; ?>
        @else
            <?php $sampleNameTag = 'packageSampleTypesSelect_'.$packageId.'_'; ?>
            {{Form::hidden('packageId'.(isset($basketProduct)?$index:''),$packageId,['class'=>'package_id'])}}
        @endif
        <p><label for="{{$sampleNameTag.(isset($basketProduct)?$basketProduct->id:'')}}"><strong>Sample type <span class="maxPoolI"
                @if (isset($basketProduct))
                    @if($basketProduct->getSelectedSampleTypeMaxPool())
                        style='display: inline-block'
                    @endif
                    >(Max pool: {{{$basketProduct->getSelectedSampleTypeMaxPool()}}})
                @endif
        </span></strong></label></p>
        <span class="JSON_sampleType">
            @if ($basketProduct->isSOP && !$basketProduct->isFOP)
                <p id="<?php echo isset($basketProduct)?$basketProduct->getSelectedSampleType():''; ?>"><?php echo isset($basketProduct)?$basketProduct->getSelectedSampleTypeLabel():''; ?></p>
            @else
                @if(isset($basketProduct) && count($basketProduct->getSampleTypesSelectOptions()) == 1)
                    <p id="{{{isset($basketProduct)?$basketProduct->getSelectedSampleType():''}}}">{{{current($basketProduct->getSampleTypesSelectOptions())}}}</p>
                @else
                    {{Form::select(
                    $sampleNameTag.(isset($basketProduct)?$basketProduct->id:''),
                    isset($basketProduct)?array_merge(['' => ''],$basketProduct->getSampleTypesSelectOptions()):[],
                    isset($basketProduct)?$basketProduct->getSelectedSampleType():'',
                    ['id'=>$sampleNameTag.(isset($basketProduct)?$basketProduct->id:''),'class'=>'sampleTypeSelect persistentInput']
                    )}}
                @endif
            @endif
        </span>
        @if ($basketProduct->isSOP)
            <p>PAIRED</p>
        @endif
        <div class="confirm">
            @if(!isset($packageId))
                {{Form::hidden('removeProductId'.(isset($basketProduct)?$index:''),(isset($basketProduct)?$basketProduct->id:''),['class'=>'JSON_id'])}}
            @else
                {{Form::hidden('packageProductId'.(isset($basketProduct)?$index:''),(isset($basketProduct)?$basketProduct->id:''),['class'=>'JSON_id'])}}
            @endif
            {{Form::submit('Confirm choice',['name'=>'refresh','id'=>'confirm_sample_type_'.(isset($basketProduct)?$index:''),'class'=>'confirm-button js-hidden push--bottom'])}}
        </div>
        @if(!isset($packageId))
            <span class="updating-basket">Updating test...</span>
            @include('submission.steps.partials.basket.result-templates.paired-options')
        @endif
    </div>
</div>
