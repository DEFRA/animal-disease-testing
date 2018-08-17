<div class="breedSearchResultTemplate" @if(!isset($breedRow)) style='display: none' @endif>
  <label class="block-label">
  	<label>
  	{{Form::radio(
    'animal_breed',
    isset($breedRow)?$breedRow->lims_code:'',
    isset($breedRow)?($breedRow->lims_code==$persistence->animal_breed):'',
    ['class'=>'JSON_lims_code persistentInput']
    )}}
    <span class="JSON_description">{{{isset($breedRow)?$breedRow->description:''}}}</span>
    </label>
</label>
</div>