<div id="NoTestsInBasket" @if(count($basketProducts))style="display: none"@endif>No tests in basket</div>

<table id="basketContainer" class="push--bottom review-basket" @if(!count($basketProducts))style="width: 700px;display:none"@endif>
    <thead>
        <tr>
            <th>Test</th>
            <th>Sample type</th>
            <th class="text--center">Maximum turnaround</th>
            <th class="text--center">Average turnaround</th>
            <th class="text--center">Qty</th>
            <th class="text--right">Price</th>
            <th class="text--center">Remove</th>
        </tr>
    </thead>
    <tbody>
        @foreach($basketProducts as $index=>$basketProduct)
        <tr class="js-basketProduct">
            <td>
                <div class="name">
                    <p>{{{$basketProduct->name}}}</p>
                    <span>{{{$basketProduct->id}}}</span>
                </div>
            </td>
            <td>
                <div class="sampleType"><p>{{{$basketProduct->getSelectedSampleTypeLabel()}}}</p></div>
            </td>
            <td class="text--center">
                <div class="turnaround test-hide"><p>{{{$basketProduct->maxTurnaround}}} {{{ 'day'.plural($basketProduct->maxTurnaround) }}} </p></div>
            </td>
            <td class="text--center">
                <div class="turnaround test-hide"><p>{{{$basketProduct->averageTurnaround}}} {{{ 'day'.plural($basketProduct->averageTurnaround) }}}</p></div>
            </td>
            <td class="text--center">
                <div class="quantity">
                    <p>{{{$basketProduct->getCountAnimalIdsSamples()}}}</p>
                </div>
            </td>
            <td class="text--right">
                <div class="price-review">
                    <p>&pound;<span class="JSON_price">{{{number_format(round($basketProduct->getPriceForAllAnimals(),2),2)}}}</span></p>
                </div>
            </td>
            <td class="text--left">
                <div class="remove removeFromBasketLink" style="display:none">
                    {{Form::hidden('removeProductId'.(isset($basketProduct)?$index:''),(isset($basketProduct)?$basketProduct->id:''),['class'=>'JSON_id'])}}
                    {{-- Form::submit('Remove test '.$basketProduct->id.' from basket',['name'=>'removeProductFromBasket'.(isset($basketProduct)?$index:''),'id'=>'removeProductFromBasket'.(isset($basketProduct)?$index:''),'class'=>'removeFromBasket button-as-link-step7'])--}}
                    <a href="#" id="removeProductFromBasket{{(isset($basketProduct)?$index:'')}}" class="removeFromBasket">Remove test {{ $basketProduct->id }} from basket</a>
                </div>
                <div class="remove js-hidden">
                    {{ link_to_route('removeProduct', 'Remove '. $basketProduct->id .' from basket', array('productId' => $basketProduct->id, 'step' => 'step7', 'draftSubmissionId' => $fullSubmissionForm->draftSubmissionId), array('class'=>'removeFromBasket' , 'id'=>"remove_product_link_".(isset($basketProduct)?$index:''))) }}
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="grid-row clear-ie7">
    <div class="column-half">
        <div class="panel-indent">
            <p>Prices are indicative only - discounts for volume testing may be applied.</p>
        </div>
    </div>
    <div class="column-half">
        <p class="text--right">Subtotal&nbsp;(<span class="basketTotalItemsCount">{{{$basket->getTotalItems()}}}</span>&nbsp;items):&nbsp; &pound;<span class="basketTotalPrice">{{{$basket->getTotalWithoutVat()}}}</span></p>
        <p class="text--right">VAT:&nbsp; &pound;<span class="basketTotalPriceVat">{{{$basket->getTotalVat()}}}</span></p>
        <p class="text--right"><strong>Total:&nbsp;&pound;</strong><strong><span class="basketTotalPriceWithVat">{{{$basket->getTotalWithVat()}}}</span></strong></p>
    </div>
</div>