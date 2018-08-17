<div id="housingSelection">
  <fieldset class="inline">
    <legend>Where are they kept? (optional)</legend>
    <div class="radioGroup row" id="housingSelectionResults">
      @if(!count($housings))
        @include('submission.steps.partials.animal-details.result-templates.housing-template')
      @endif
      @foreach($housings as $housing)
        @include('submission.steps.partials.animal-details.result-templates.housing-template', ['housing'=>$housing])
      @endforeach
    </div>
  </fieldset>
  <hr />
</div>