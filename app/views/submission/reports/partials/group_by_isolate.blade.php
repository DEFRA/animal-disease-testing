<?php
$max = sizeof($section['SeparateIsolates']) - 1;
$count = 0;
?>

@foreach ($section['SeparateIsolates'] as $key => $separate)

    <?php $ignoreFootnotes = $max > $count++ ? true : false?>

    @if ($section['Type'] == 'GENERIC')

        <span class="report-marker">Section: GENERIC</span>

        @include('submission.reports.partials.generic',['section' => $section, 'isolates' => $separate['Isolates'], 'subSections' => $separate['SubSections'], 'ignoreFootnotes' => $ignoreFootnotes])

    @elseif ($section['Type'] == 'ISOLATE')

        <span class="report-marker">Section: ISOLATE</span>

        @include('submission.reports.partials.isolate',['section' => $section, 'isolates' => $separate['Isolates'], 'subSections' => $separate['SubSections'], 'ignoreFootnotes' => $ignoreFootnotes])

    @elseif ($section['Type'] == 'SAMPLE')

        <span class="report-marker">Section: SAMPLE</span>

        @include('submission.reports.partials.sample',['section' => $section, 'isolates' => $separate['Isolates'], 'subSections' => $separate['SubSections'], 'ignoreFootnotes' => $ignoreFootnotes])

    @endif

@endforeach