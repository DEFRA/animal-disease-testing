@if(isset($disease))
<div class="adviser-wrapper-template">
    <table>
        <thead>
            <tr>
                <th>Clinical sign</th>
                <th>Age category</th>
                <th>Condition/cause</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="JSON_disease">{{{isset($disease)?$disease:''}}}</td>
                <td class="JSON_age_category">{{{isset($ageCategory)?$ageCategory:''}}}</td>
                <td class="JSON_condition_cause">{{{isset($conditionCause)?$conditionCause:''}}}</td>
            </tr>
        </tbody>
    </table>

    @foreach($sampleTypesArray as $sampleType=>$arr)
        
        @if (isset($arr))
            <ul class="testSearchResult-list">
            @foreach($arr['tests'] as $productId)

                @if(array_key_exists($productId, $adviceSearchResults['products']))
    
                    @include('submission.steps.partials.tests.result-templates.test-template', ['testRow'=>$adviceSearchResults['products'][$productId]])

                @else
    
                    <li class="testSearchResultTemplate missing">
                        <p>TEST MISSING ({{{$productId}}})</p>
                    </li>
    
                @endif
    
            @endforeach
            </ul>

            @if (array_key_exists('furtherInfo', $arr) && $arr['furtherInfo'] !== '' )
                <div class="footer">
                    <dl>
                        <dt>Further information</dt>
                        <dd>{{{$arr['furtherInfo']}}}</dd>
                    </dl>
                </div>
            @endif
            
        @endif

    @endforeach
    
</div>
@endif

