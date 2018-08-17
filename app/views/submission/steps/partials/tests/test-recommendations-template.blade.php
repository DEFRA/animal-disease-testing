@if(array_key_exists('recommendations', $adviceSearchResults))

    @foreach($adviceSearchResults['recommendations'] as $disease=>$ageCategoryArray)

        @foreach($ageCategoryArray as $ageCategory=>$conditionCauseArray)

            @foreach($conditionCauseArray as $conditionCause=>$sampleTypesArray)

                @include('submission.steps.partials.tests.result-templates.test-recommendation-template', compact('disease', 'ageCategory', 'conditionCause', 'sampleTypesArray', 'adviceSearchResults'))

            @endforeach

        @endforeach

    @endforeach

@endif