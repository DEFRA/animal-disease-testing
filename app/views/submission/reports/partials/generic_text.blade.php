<h3>{{ $subSection['Name'] }}</h3>

<div class="row push--bottomq">
@foreach ($subSection['Results'] as $currentResult)
    <p class="font-xsmall">{{ $currentResult['Result'] }}</p>
@endforeach
</div>