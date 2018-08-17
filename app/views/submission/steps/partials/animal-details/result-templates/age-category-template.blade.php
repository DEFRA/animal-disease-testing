<label class="ageCategoryResultRef block-label @if(!isset($age_cat)) hidden @endif">
<span>
<label>
  {{Form::radio(
    'age_category',
    isset($age_cat)?$age_cat->lims_code:'',
    isset($age_cat)?($age_cat->lims_code==$persistence->age_category):'',
    ['id'=>isset($age_cat)?$age_cat->lims_code:'', 'class'=>'JSON_lims_code persistentInput']
    )}}
  <span class="JSON_description">{{{isset($age_cat)?$age_cat->description:''}}}</span>
 </label>
</span>
</label>