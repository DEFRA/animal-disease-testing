{{-- header table --}}

<table>

    <tr>
        <td><strong>Senders Sample Ref</strong></td>
        <td><strong>APHA Sample Ref</strong></td>
        <td><strong>Sample Type</strong></td>
    </tr>

    @foreach ($isolates as $isolate)

        <tr>
            <td>
                {{ $isolate['SampleReference'] }}
            </td>
            <td>
                {{ $isolate['Reference'] }}
            </td>
            <td>
                {{ $isolate['Site'] }}
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

        @include('submission.reports.partials.sample_standard',[ 'subSection'=>$subSection,'isolates'=>$subSection ])

    @elseif ( $subSection['Type'] == 'TEXT' )

        @include('submission.reports.partials.sample_text',[ 'subSection'=>$subSection ])

    @elseif ( $subSection['Type'] == 'PIVOT' )

        @include('submission.reports.partials.sample_pivot',[ 'subSection'=>$subSection,'isolates'=>$subSection ])

    @elseif ( $subSection['Type'] == 'SENTEST' )

        @include('submission.reports.partials.sample_sentest',[ 'subSection'=>$subSection ])

        <p class="font-xsmall">Test Results: S - Sensitive&nbsp;&nbsp;&nbsp;R - Resistant</p>

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

    <span class="report-marker push--left">Subsection: SAMPLE - Footnotes</span>

    @if (!empty($section['Footnotes']))
        <h4>Footnotes</h4>
        @foreach ($section['Footnotes'] as $footnote)
            <div>{{ $footnote['Footnote']}}</div>
        @endforeach
    @endif

    <span class="report-marker push--left">Subsection: SAMPLE - Comments</span>

    @if (!empty($section['Comments']))
        <h4>Comments</h4>

        <table>
        <tbody>

        <tr>
            <td><strong>Sample Reference</strong></td>
            <td><strong>Comment</strong></td>
        </tr>

        @foreach ($section['Comments'] as $comment)
            @if ( is_string($comment) )
                <tr><td colspan="2">{{ $comment }}</td></tr>
            @elseif( is_array($comment) )

                <tr>
                    <td>@if ( isset($comment['SampleReference']) ) {{ $comment['SampleReference'] }}  @endif</td>
                    <td>@if ( isset($comment['Comment']) ) {{ $comment['Comment'] }}  @endif</td>
                </tr>

            @endif
        @endforeach

        </tbody>
        </table>

    @endif

@endif

