{{-- header table --}}

<table>

    <tr>
        <td><strong>Species</strong></td>
        <td><strong>Sample Ref</strong></td>
        <td><strong>Site</strong></td>
        <td><strong>Isolate</strong></td>
        <td><strong>Isolate Ref</strong></td>
    </tr>

    @foreach ($isolates as $isolate)

        <tr>
            <td>
                {{ $isolate['Species'] }}
            </td>
            <td>
                {{ $isolate['SampleReference'] }}
            </td>
            <td>
                {{ $isolate['Site'] }}
            </td>
            <td>
                {{ $isolate['Name'] }}
            </td>
            <td>
                {{ $isolate['Reference'] }}
            </td>
        </tr>

        <?php

            // we need to save the footnote for display after the table
            $reference_source = preg_replace("/[^a-zA-Z0-9]+/", "", $isolate['Reference']);
            if (!empty($isolate['Footnote'])) {
                $footnotes[$reference_source] = $isolate['Footnote'];
            }

        ?>

    @endforeach

</table>

@foreach ($subSections as $subSection)

    <span class="report-marker push--left">Subsection: {{$subSection['Type']}}</span>

    <h3>{{ $subSection['Name'] }}</h3>

    @if ( $subSection['Type'] == 'STANDARD' )

        @include('submission.reports.partials.isolate_standard',[ 'subSection'=>$subSection,'isolates'=>$subSection ])

    @elseif ( $subSection['Type'] == 'TEXT' )

        @include('submission.reports.partials.isolate_text',[ 'subSection'=>$subSection ])

    @elseif ( $subSection['Type'] == 'PIVOT' )

        @include('submission.reports.partials.isolate_pivot',[ 'subSection'=>$subSection,'isolates'=>$subSection ])

    @elseif ( $subSection['Type'] == 'SENTEST' )

        @include('submission.reports.partials.isolate_sentest',[ 'subSection'=>$subSection ])

    @endif

    <?php

        // after each sub section test, we see if there are notes relating to it
        // we use the first entry as the reference key
        if (isset($subSection['Results'][0])) {
            if (isset($subSection['Results'][0]['SampleName'])) {
                // see above same regex line
                $reference_target = preg_replace("/[^a-zA-Z0-9]+/", "", $subSection['Results'][0]['SampleName']);
                if (isset($footnotes[$reference_target])) {
                    echo $footnotes[$reference_target];
                }
            }
        }

    ?>

@endforeach


@if (!isset($ignoreFootnotes) || $ignoreFootnotes == false)

    <span class="report-marker push--left">Subsection: ISOLATE - Footnotes</span>

    @if (!empty($section['Footnotes']))
        <h4>Footnotes</h4>
        @foreach ($section['Footnotes'] as $footnote)
            <div>{{ $footnote['Footnote']}}</div>
        @endforeach
    @endif

    <span class="report-marker push--left">Subsection: ISOLATE - Comments</span>

    @if (!empty($section['Comments']))
        <h4>Comments</h4>
        @foreach ($section['Comments'] as $comment)
            @if ( is_string($comment) )
                <p class="font-xsmall">{{ $comment }}</p>
            @elseif( is_array($comment) )
                <p class="font-xsmall">
                @foreach ($comment as $title => $indivComment)
                    <ul><strong>{{ $title }}:</strong>&nbsp;{{ $indivComment }}</ul>
                @endforeach
                </p>
            @endif
        @endforeach
    @endif

@endif


