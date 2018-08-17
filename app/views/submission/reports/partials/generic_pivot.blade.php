<table>

    <tr>
        <td><strong>Tests</strong></td>

        @foreach ($subSection['Results'] as $currentResult)

            <td>
                <strong>
                {{ $currentResult['SampleName'] }}
                </strong>
            </td>

        @endforeach
    </tr>

    <tr>
        <td>
            {{-- we take the first entry --}}
            @if ( $subSection['Results'][$i][0] )
                {{ $subSection['Results'][$i][0]['TestName'] }}
            @endif
        </td>

        @for ($i = 0; $i < sizeof($subSection['Results']); $i++)
            <td>
                {{ $subSection['Results'][$i]['Result'] }}
            </td>
        @endfor

    </tr>

</table>