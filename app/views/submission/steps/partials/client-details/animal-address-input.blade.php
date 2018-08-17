<fieldset>
    <?php $sop = (isset($isSop) && $isSop)?'sop_':''; ?>

    @if (!$sop)
    <legend>Fill in new animal address details</legend>
        <p>If you have the full CPH this can be used to retrieve the animal address record.</p>
    @endif

    <div id="animal-cphh-input" @if (!$sop) @if($persistence->isIsEditAnimalAddressMode())style="display: none" @endif @endif>
        <label for="animal_cphh">Animal CPH eg 48/234/2348:</label>
        {{Form::text(
            $sop.'animal_cphh',
            $persistence->{$sop.'animal_cphh'},
            ['id'=>$sop.'animal_cphh','class'=>'persistentInput form-control js-cphh push--bottom','autocomplete' => 'off']
        )}}
    </div>

    <label for="animal_farm">Farm Name:</label>
    {{Form::text(
        $sop.'animal_farm',
        $persistence->{$sop.'animal_farm'},
        ['id'=>$sop.'animal_farm','class'=>'persistentInput form-control push--bottom','autocomplete' => 'off']
    )}}

    <label for="animal_address1">Address:</label>
    {{Form::text(
        $sop.'animal_address1',
        $persistence->{$sop.'animal_address1'},
        ['id'=>$sop.'animal_address1','class'=>'persistentInput form-control push--bottom','autocomplete' => 'off']
    )}}
    <label for="animal_address2" class="visuallyhidden">Address 3:</label>
    {{Form::text(
        $sop.'animal_address2',
        $persistence->{$sop.'animal_address2'},
        ['id'=>$sop.'animal_address2','class'=>'persistentInput form-control push--bottom','autocomplete' => 'off']
    )}}
    <label for="animal_address3" class="visuallyhidden">Address 4:</label>
    {{Form::text(
        $sop.'animal_address3',
        $persistence->{$sop.'animal_address3'},
        ['id'=>$sop.'animal_address3','class'=>'persistentInput form-control push--bottom','autocomplete' => 'off']
    )}}

    {{Form::hidden($sop.'animal_address4','',[])}}
    {{Form::hidden($sop.'animal_address5','',[])}}

    <label for="animal_county">County:</label>
    {{Form::select(
        $sop.'animal_county',
        $select_counties_elements,
        $persistence->{$sop.'animal_county'},
        ['id'=>$sop.'animal_county','class'=>'persistentInput form-control push--bottom','autocomplete' => 'off'],
        ''
    )}}

    <label for="animal_postcode">Postcode:</label>
    {{Form::text(
        $sop.'animal_postcode',
        $persistence->{$sop.'animal_postcode'},
        ['id'=>$sop.'animal_postcode','class'=>'persistentInput form-control push--bottom','autocomplete' => 'off']
    )}}

    @if (!$sop)
        <p>
            <a id="cancel-animals" href="#" class="js-searchAnimalsButton push--top no-js-hide">Cancel</a>
        </p>
    @endif
</fieldset>