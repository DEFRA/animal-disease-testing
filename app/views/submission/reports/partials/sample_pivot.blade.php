<table class="full-width">
    <thead>
        <tr>
        @foreach ($subSection['Results'] as $currentResult)
            <th>{{ $currentResult['TestName'] }}</th>
        @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
        @foreach ($subSection['Results'] as $currentResult)
            <td>{{ $currentResult['Result'] }}</td>
        @endforeach
        </tr>
    </tbody>
</table>