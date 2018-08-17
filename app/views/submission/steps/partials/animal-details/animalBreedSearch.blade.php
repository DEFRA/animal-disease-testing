<div id="animalBreedSearch">
    <fieldset class="inline">
        <legend class="visuallyhidden">Breed Search</legend>
        <label for="breedSearchInput" class="legend">Breed (optional)</label>
        <div class="row">
            <input type="text" name="breedSearchInput" id="breedSearchInput" placeholder="start typing" class="persistentInput" autocomplete="off" value="{{{$persistence->breedSearchInput}}}">
            <input class="search-button js-hidden" type="submit" name="refresh" value="Search">
        </div>
        <div class="row panel-indent flush--top" id="breedSearchResults">
            @if(!count($breeds))
                @include('submission.steps.partials.animal-details.result-templates.breed-template')
                @if(isset($persistence->breedSearchInput) && $persistence->breedSearchInput != '')
                    <p>No results found.</p>
                @endif
            @endif

            @foreach($breeds as $breedRow)
                @include('submission.steps.partials.animal-details.result-templates.breed-template', ['breedRow'=>$breedRow])
            @endforeach
        </div>
    </fieldset>
    <hr />
</div>