<table>

    @foreach ($subSection['Results'] as $currentResult)
        <tr>
            <td>{{ $currentResult['TestName'] }}</td>
            <td>{{ $currentResult['Result'] }}</td>
        </tr>
    @endforeach

</table>