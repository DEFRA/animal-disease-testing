<label class="block-label inline">
	{{Form::checkbox(
	    'productOption_'.(isset($basketProduct)?$basketProduct->id:'').'_'.(isset($option)?$option->id:''),
	    1,
	    isset($option)?$basketProduct->isOptionSelected($option):false,
	    ['class'=>'persistentInput'])}}
    

    {{{isset($option)?$option->getOptionLabel():''}}}
</label>