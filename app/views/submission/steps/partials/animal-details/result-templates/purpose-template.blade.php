<label class="purposeSelectionResultRef block-label @if(!isset($purpose)) hidden @endif">
<span>
<label>
  {{Form::radio(
    'purpose',
    isset($purpose)?$purpose->lims_code:'',
    isset($purpose)?($purpose->lims_code==$persistence->purpose):'',
    ['id'=>isset($purpose)?$purpose->lims_code:'', 'class'=>'JSON_lims_code persistentInput']
    )}}
  <span class="JSON_description">{{{isset($purpose)?$purpose->description:''}}}</span>
</label>
</span>
</label>