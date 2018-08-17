<table class="full-width">

    <thead>
        <tr>
            <th>Test Name</th>
            <th>Sample ID</th>
            <th>Result</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($subSection['Results'] as $currentResult)
        <tr>
            <td>{{ $currentResult['TestName'] }}</td>
            <td>{{ $currentResult['SampleName'] }}</td>
            <td>{{ $currentResult['Result'] }}</td>
        </tr>
        @endforeach
    </tbody>

</table>