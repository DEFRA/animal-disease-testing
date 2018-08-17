@if(isset($basketProduct))
    <?php $fopSop = ($basketProduct->isFOP && $basketProduct->isSOP)?true:false; ?>
    <?php $sop = $basketProduct->isSOP?true:false; ?>
    <div data-id="js-singleTest" class="basket-table js-basketProduct" @if(!isset($basketProduct)) style='display: none' @endif>
        @include('submission.steps.partials.basket.result-templates.basketProduct-item')
        @include('submission.steps.partials.basket.result-templates.product-animal-sample')
        @if($sop && !$fopSop)
        @else
            @include('submission.steps.partials.basket.result-templates.paired-animal-samples')
        @endif
        @include('submission.steps.partials.basket.result-templates.product-remove-button')
    </div>
@endif