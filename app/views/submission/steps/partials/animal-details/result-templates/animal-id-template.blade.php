<div class="animalIdTemplateRef" @if(!isset($animalIdIndex)) style="display:none"@endif>
    <div class="column-third">
        <div class="form-group">
            <?php $name= isset($animalIdIndex)?'animal_id'.$animalIdIndex:'';?>
            <label for="{{{$name}}}" class="JSON_label_animal_ids">
                animal <span class="JSON_animalIdIndex">{{{isset($animalIdIndex)?($animalIdIndex+1):''}}}:</span>
            </label>
            {{Form::text($name,isset($animalIdIndex)?$persistence->$name:'',['class'=>'JSON_animal_ids persistentInput','id' => $name,'autocomplete' => 'off']);}}
        </div>
    </div>
</div>