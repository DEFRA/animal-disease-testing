
<div class="animal-id-list paired-animal-samples {{{ $basketProduct->isFOP && $basketProduct->isSOP ? '' : 'hidden' }}}">
    <div class="basket-table header">
        <div class="pair-sample-id">
            <p>Add 2nd Sample ID<br>(optional)</p>
        </div>
    </div>

    <div class="basket-table body">
        @foreach($basketProduct->animalIdsSamples as $animalIdSample)
            @if($animalIdSample->isSOP)
                <div class="animal-row product_animal_sample_id_SOP">
                    <div class="pair-sample-id">
                        @if(isset($readOnlyMode))
                            {{{(isset($animalIdSample)?$animalIdSample->sampleId:'')}}}
                        @else
                            <label for="{{{'sampleid_'.(isset($animalIdSample)?$animalIdSample->animal->id:'').'_SOP'}}}" class="visuallyhidden">{{{'sampleid_'.(isset($animalIdSample)?$animalIdSample->animal->id:'').'_SOP'}}}</label>
                            {{Form::text(
                                'sampleid_'.(isset($animalIdSample)?$animalIdSample->animal->id:'').'_'.(isset($basketProduct)?$basketProduct->id:'').'_SOP',
                                (isset($animalIdSample)?$animalIdSample->sampleId:''),
                                ['id'=>'sampleid_'.(isset($animalIdSample)?$animalIdSample->animal->id:'').'_SOP','class'=>'persistentInput sampleId form-control','autocomplete' => 'off'])}}
                        @endif
                    </div>
                    <div class="animal-id">
                        <p>
                            <span class="JSON_animalId">{{{isset($animalIdSample)?$animalIdSample->animal->description:''}}}</span>
                        </p>
                    </div>
                    <div class="pool">
                        <p></p>
                    </div>
                    <div class="price-col">
                        <p>&pound;<span class="JSON_price">{{{isset($basketProduct)?number_format(round($basketProduct->price,2),2):''}}}</span></p>
                    </div>
                </div>

                <div class="clear"></div>
            @endif
        @endforeach
    </div>
</div>