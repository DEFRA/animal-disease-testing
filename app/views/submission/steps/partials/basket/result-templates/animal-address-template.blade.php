<div id="animals-address-prev" class="animal-address" @if($persistence->animals_at_address === '0')style="display:none"@endif>
	<div class="animal-address__wrap">
	    <p class="animal-address__name">{{{ $clientDetails->fop_animal_farm }}}</p>
	    <p class="animal-address__address">{{{ $clientDetails->fop_animal_address1 }}}</p>
        <p class="animal-address__address">{{{ $clientDetails->fop_animal_address2 }}}</p>
        <p class="animal-address__address">{{{ $clientDetails->fop_animal_address3 }}}</p>
        <p class="animal-address__address">{{{ $clientDetails->fop_animal_county }}}</p>
	    <p class="animal-address__postcode">{{{ $clientDetails->fop_animal_postcode }}}</p>
	    <p class="animal-address__cph">{{{ $clientDetails->fop_animal_cphh }}}</p>
    </div>
    <hr class="push-double--top push-double--bottom">
</div>