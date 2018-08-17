<div id="sexGroupSelection">
  <fieldset class="inline">
    <legend>Sex (optional)</legend>
    <div class="radioGroup row">
      <div name="sexGroupSelectionResults" id="sexGroupSelectionResults">
        @foreach($sexGroups as $sexGroup)
          <label class="block-label sexGroupSelectionResultRef">
            {{Form::radio('sexGroup',$sexGroup->lims_code,($sexGroup->lims_code==$persistence->sexGroup),['id'=>$sexGroup->lims_code, 'class'=>'JSON_lims_code persistentInput'])}}
            <span class="JSON_description">{{{$sexGroup->description}}}</span>
          </label>
        @endforeach
      </div>
    </div>
  </fieldset>
  <hr />
</div>