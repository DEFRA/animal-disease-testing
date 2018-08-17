@extends('layouts.master')
@section('title', 'Test Results -')
@section('content')

<h2>Test results</h2>

<div class="report">

    @include('submission.reports.partials.header', $results)

    @include('submission.reports.partials.summary', $submission)

    <h3>LABORATORY FINDINGS</h3>

        @if ( isset($results['SubmissionHeader']) )
        @if ( is_string($results['SubmissionHeader']) )
            <h5>{{ $results['SubmissionHeader'] }}</h5>
        @endif
        @endif

        @if ( isset($results['SubmissionComments']) )
            @foreach ($results['SubmissionComments'] as $comment)
                <ul>{{ $comment['Comment'] }}</ul>
            @endforeach
        @endif

        @if ( is_array($results) )

            @if ( isset($results['Sections']) )

                @foreach ($results['Sections'] as $currentSection)

                    <h2 class="push-double--top">{{ $currentSection['Name'] }}</h2>

                    @if (isset($currentSection['SeparateIsolates']) && !empty($currentSection['SeparateIsolates']))
                        @include('submission.reports.partials.group_by_isolate', ['section' => $currentSection])
                    @else
                        @include('submission.reports.partials.group_by_default', ['section' => $currentSection])
                    @endif

                @endforeach

            @endif

            @if ( isset($results['SampleComments']) && !empty($results['SampleComments']))

                <table>
                <tbody>

                <tr>
                    <td><strong>Sample reference</strong></td>
                    <td><strong>Comment</strong></td>
                </tr>

                @foreach ($results['SampleComments'] as $comment)
                    <span class="report-marker">Sample comments</span>
                    @if ( is_string($comment) )
                        <tr><td colspan="2">{{ $comment }}</td></tr>
                    @elseif( is_array($comment) )

                        <tr>
                            <td>@if ( isset($comment['SampleRefOrder']) ) {{ $comment['SampleRefOrder'] }}  @endif</td>
                            <td>@if ( isset($comment['Comment']) ) {{ $comment['Comment'] }}  @endif</td>
                        </tr>

                    @endif
                @endforeach

                </tbody>
                </table>

            @endif

            @if ( isset($results['VioComment']) && !empty($results['VioComment']))
                <span class="report-marker">VIO comment</span>
                <h3>VIO Comment</h3>
                <p class="font-xsmall">{{ $results['VioComment'] }}</p>
            @endif

            @if ( isset($results['WorkInProgress']) && !empty($results['WorkInProgress']))
                <span class="report-marker">Work in Progress</span>
                <h3>Work in Progress</h3>
                @foreach ($results['WorkInProgress'] as $work)
                    @if ( is_string($work) )
                        <ul>{{ $work }}</ul>
                    @elseif( is_array($work) )

                        @if ( isset($work['TestCode']) ) {{ $work['TestCode'] }}  @endif
                        &nbsp;
                        @if ( isset($work['Description']) ) {{ $work['Description'] }}  @endif
                        &nbsp;
                        @if ( isset($work['Quantity']) ) x {{ $work['Quantity'] }}  @endif

                        <br />
                    @endif
                @endforeach
            @endif

            @if ( isset($results['SubmissionFooter']) )
            @if ( is_string($results['SubmissionFooter']) )
                <p>{{ $results['SubmissionFooter'] }}</p>
            @endif
            @endif

            @if (isset($results['Charges']) && is_array($results['Charges']) && sizeof($results['Charges']) > 0)

                <div class="report-charges">
                    The charge for this laboratory work is <strong>&#163;{{number_format($results['ChargesTotal'], 2)}}</strong> plus
                    VAT if applicable. This will be included in your monthly statement. Service charge codes:
                    @foreach ($results['Charges'] as $key => $charge)
                        @if ($key > 0)
                            ,
                        @endif
                        {{$charge['ServiceName']}} x {{$charge['Quantity']}}
                    @endforeach
                </div>
            @endif

        @endif

    @include('submission.reports.partials.footer')
</div>

@stop