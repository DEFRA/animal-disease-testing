<table>
    @foreach ($subSection['Results'] as $currentResult)
    <tr>
        <th>{{ $currentResult['TestName'] }}</th>
        <td>{{ $currentResult['Result'] }}</td>
    </tr>
    @endforeach
</table>