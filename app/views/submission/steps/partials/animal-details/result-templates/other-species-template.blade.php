<div class="speciesSearchResultTemplate" @if(!isset($otherSpeciesRow)) style='display: none' @endif>
  <label class="block-label">
	  {{Form::radio(
	    'other_species',
	    isset($otherSpeciesRow)?$otherSpeciesRow->lims_code:'',
	    isset($otherSpeciesRow)?($otherSpeciesRow->lims_code==$persistence->other_species):'',
	    ['class'=>'JSON_lims_code radio persistentInput']
	    )}}}
        <span class="JSON_description">{{{isset($otherSpeciesRow)?$otherSpeciesRow->description:''}}}</span>
	</label>
</div>