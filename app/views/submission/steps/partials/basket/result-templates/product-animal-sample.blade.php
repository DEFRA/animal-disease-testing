<div class="animal-id-list animalSamplesList">
    @if(!isset($basketProduct))
        @include('submission.steps.partials.basket.result-templates.sub-templates.product-animal-id-template',['hiddenReference'=>true])
    @else
        @foreach($basketProduct->animalIdsSamples as $animalIdSample)
                @if (!$animalIdSample->isSOP || (!$basketProduct->isFOP && $basketProduct->isSOP))
                    @include('submission.steps.partials.basket.result-templates.sub-templates.product-animal-id-template',
                    ['animalIdSample'=>$animalIdSample,'animalIdSampleCount'=>count($basketProduct->animalIdsSamples),'poolGroup' => $animalIdSample->poolGroup, 'disabled' => $animalIdSample->poolGroupDisabled])
                @endif
        @endforeach
    @endif
    <div id="validationGlobalDiv" class="groups__validation">
        <p></p>
    </div>
</div>

@if(isset($basketProduct))
    <div class="groups" @if($basketProduct->selectedSampleTypeMaxPool) style='display: block' @endif>
        <div class="groups__row--groups">
            <?php $poolGroups = $basketProduct->getNumberOfPoolGroups(); ?>
            @for ($i = 1; $i <= $poolGroups; $i++)
            <div class="groups__row">
                <div class="groups__group">
                    <p>Pool group <span>{{{$i}}}</span></p>
                </div>
                <div class="groups__price">
                    <p>&pound;<span class="JSON_price">{{{$basketProduct->price}}}</span></p>
                </div>
            </div>
            @endfor
        </div>
        <div class="groups__row--total">
            <div class="groups__total"><p>Total:</p></div>
            <div class="groups__totalprice"><p>&pound;{{{$basketProduct->getPooledTotal()}}}</p></div>
        </div>
    </div>
@endif
