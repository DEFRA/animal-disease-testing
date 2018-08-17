@foreach ($subSections as $subSection)

    <span class="report-marker push--left">Subsection: {{$subSection['Type']}}</span>

    <h3>{{ $subSection['Name'] }}</h3>

    @if ( $subSection['Type'] == 'STANDARD' )

        @include('submission.reports.partials.generic_standard',[ 'subSection'=>$subSection,'isolates'=>$subSection ])

    @elseif ( $subSection['Type'] == 'TEXT' )

        @include('submission.reports.partials.generic_text',[ 'subSection'=>$subSection ])

    @elseif ( $subSection['Type'] == 'PIVOT' )

        @include('submission.reports.partials.generic_pivot',[ 'subSection'=>$subSection,'isolates'=>$subSection ])

    @elseif ( $subSection['Type'] == 'SENTEST' )

        @include('submission.reports.partials.generic_sentest',[ 'subSection'=>$subSection ])

    @endif

@endforeach


@if (!isset($ignoreFootnotes) || $ignoreFootnotes == false)

    <span class="report-marker push--left">Subsection: GENERIC - Footnotes</span>

    @if (!empty($section['Footnotes']))
        <h4>Notes</h4>
        @foreach ($section['Footnotes'] as $footnote)
            <div class="generic-footnote push-double--bottom">{{ $footnote['Footnote'] }}</div>
        @endforeach
    @endif

    <span class="report-marker push--left">Subsection: GENERIC - Comments</span>

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