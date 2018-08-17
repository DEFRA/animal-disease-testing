
<label class="diseaseSelectionResultRef block-label @if(!isset($disease)) hidden @endif">
  {{Form::radio(
    'disease',
    isset($disease)?$id:'',
    isset($disease)?$id==$persistence->disease:'',
    ['id'=>isset($disease)?$id:'','class'=>'JSON_id radio persistentInput']
    )}}
    <span class="JSON_disease">{{{isset($disease)?$disease:''}}}</span>
</label>