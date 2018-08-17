<table>

    <tr>
        <td><strong>Antimicrobial</strong></td>
        <td><strong>Result</strong></td>
    </tr>

    @foreach ($subSection['Results'] as $currentResult)

        <tr>
            <td>
                {{ $currentResult['TestName'] }}
            </td>
            <td>
                {{ $currentResult['Result'] }}
            </td>
        </tr>

    @endforeach

</table>