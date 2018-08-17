<li class="testSearchResultTemplate<?php if(!isset($index) || $index%2){echo " evenRow";}?>" @if(!isset($testRow)) style='display: none' @endif>
  <div class="test-row">
    <div class="name">
      <h3><span class="JSON_name">{{{isset($testRow)?$testRow->name:''}}}</span>&nbsp;<br /></h3>
      <h4>(<span class="JSON_id">{{{isset($testRow)?$testRow->id:''}}}</span>)</h4>
    </div>
    <div class="turnaround">
        <p>
        Maximum turnaround days: <span class="JSON_maxTurnaround">{{{isset($testRow)?$testRow->maxTurnaround:''}}}</span><br />
        Average turnaround days: <span class="JSON_averageTurnaround">{{{isset($testRow)?$testRow->averageTurnaround:''}}}</span>
        </p>
    </div>
    <div class="clear"></div>
    <div class="sample-type">
      <p><strong>Sample type:</strong><br />
      <span class="JSON_sampleTypes">{{{isset($testRow)?$testRow->sampleTypes:''}}}</span></p>
    </div>
    <div class="test-type">
      <p><strong>Test type:</strong><br />
      <span class="JSON_type">{{{isset($testRow)?$testRow->type:''}}}</span></p>
    </div>
    <div class="accreditation">
      <p><strong>UKAS accredited:</strong><br />
          <span class="JSON_accredited">{{{isset($testRow)?$testRow->accredited:''}}}</span></p>
    </div>
    <div class="species">
      <p><strong>Species:</strong><br />
      <span class="JSON_species">{{{isset($testRow)?$testRow->species:''}}}</span></p>
    </div>
    <div class="price">
        <p>Single price <span class="large">&pound;<span class="JSON_price">{{{isset($testRow)?$testRow->price:''}}}</span></span></p>
        <?php $rand = rand(); ?>
        {{Form::hidden('productId'.(isset($index)?$index:$rand),isset($testRow)?$testRow->id:'')}}
        <button type="submit" class="addProductToBasket button" name ="addProductToBasket{{{(isset($index)?$index:$rand)}}}">
            Add <span class="JSON_addProductToBasketId">{{{(isset($testRow)?$testRow->id:'')}}}</span> To Basket
        </button>
    </div>
    <div class="clear"></div>
  </div>
</li>