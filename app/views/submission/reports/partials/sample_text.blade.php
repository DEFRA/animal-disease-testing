<div class="row push--bottom">
@foreach ($subSection['Results'] as $currentResult)
    <p class="font-xsmall">{{ $currentResult['Result'] }}</p>
@endforeach
</div>