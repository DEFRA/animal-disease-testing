<label class="housingSelectionResultRef block-label @if(!isset($housing)) hidden @endif">
	<span>
	<label>
	  {{Form::radio(
	    'housing',
	    isset($housing)?$housing->lims_code:'',
	    isset($housing)?($housing->lims_code==$persistence->housing):'',
	    ['class'=>'JSON_lims_code persistentInput']
	    )}}
	  <span class="JSON_description">{{{isset($housing)?$housing->description:''}}}</span>
	</label>
	</span>
</label>