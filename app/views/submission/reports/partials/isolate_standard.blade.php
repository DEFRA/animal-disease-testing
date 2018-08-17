<table>

    <tr>
        @foreach ($subSection['Results'] as $currentResult)
            <td>{{ $currentResult['TestName'] }}</td>
        @endforeach
    </tr>

    <tr>
        @foreach ($subSection['Results'] as $currentResult)
            <td>{{ $currentResult['Result'] }}</td>
        @endforeach
    </tr>

</table>