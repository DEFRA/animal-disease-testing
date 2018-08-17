<table>
    <thead>
        <tr>
            <th>Antimicrobial</th>
            <th>Result</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($subSection['Results'] as $currentResult)

        <tr>
            <td>{{ $currentResult['TestName'] }}</td>
            <td>{{ $currentResult['Result'] }}</td>
        </tr>

    @endforeach    
    </tbody>

</table>