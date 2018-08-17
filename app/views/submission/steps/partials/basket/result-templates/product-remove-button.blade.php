<div class="remove removeFromBasketLink push--top" style="display:none">
    <a href="#" id="remove_product_link_{{{ (isset($basketProduct)?$index:'')}}}" class="removeFromBasket">Remove {{{ $basketProduct->id }}} from basket</a>
</div>
<div class="remove js-hidden push--top">
    {{ link_to_route('removeProduct', 'Remove '. $basketProduct->id .' from basket', array('productId' => $basketProduct->id,'step' => 'step5','draftSubmissionId' => $fullSubmissionForm->draftSubmissionId), array('class'=>'removeFromBasket' , 'id'=>"remove_product_link_".(isset($basketProduct)?$index:''))) }}
</div>