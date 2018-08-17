@if ($section['Type'] == 'GENERIC')

    <span class="report-marker">Section: GENERIC</span>

    @include('submission.reports.partials.generic',['section' => $section, 'isolates' => $section['Isolates'], 'subSections' => $section['SubSections']])

@elseif ($section['Type'] == 'ISOLATE')

    <span class="report-marker">Section: ISOLATE</span>

    @include('submission.reports.partials.isolate',['section' => $section, 'isolates' => $section['Isolates'], 'subSections' => $section['SubSections']])

@elseif ($section['Type'] == 'SAMPLE')

    <span class="report-marker">Section: SAMPLE</span>

    @include('submission.reports.partials.sample',['section' => $section, 'isolates' => $section['Isolates'], 'subSections' => $section['SubSections']])

@endif