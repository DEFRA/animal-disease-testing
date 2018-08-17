<?php

    $totalItems = $limsPaginator->totalItemsCount;
    $previousPage = $limsPaginator->previousPage();
    $nextPage = $limsPaginator->nextPage();
    $totalPages = $limsPaginator->totalPages();
    $currentPage = $limsPaginator->page;
    $totalRecordsCount = $limsPaginator->totalItemsCount;

?>

<div id="submissions-box">

@if(isset($totalRecordsCount))
    <p>{{{$totalRecordsCount}}}
    @if($totalRecordsCount == 1)
        Submission
    @else
        Submissions
    @endif       
    </p>
@endif

@foreach($submissionList as $index=>$submission)
    @include('login.partials.submission-template',['submission'=>$submission])
@endforeach

<?php
    $filters = '';

    if ( !empty($input['clientId']) ) { $filters .= '&clientId='.$input['clientId']; }
    if ( !empty($input['status']) ) { $filters .= '&status='.$input['status']; }
    if ( !empty($input['clinician']) ) { $filters .= '&clinician='.$input['clinician']; }
    if ( !empty($input['date']) ) { $filters .= '&date='.$input['date']; }
?>

<ul class="previous-next-navigation">
    <li id="page-left-nav" class="previous">
        @if(isset($previousPage)&&$previousPage>0)
            <a page="{{ $previousPage }}" class="test_search_input_link hide-link" id="previous-page" next_page="{{ $currentPage-1 }}" href="/landing?page={{ $currentPage-1 }}{{{ $filters }}}">Previous <span class="visuallyhidden">page</span>
                <span class="page-numbers">{{{ $currentPage }}} of {{{ $totalPages }}}</span>
            </a>
        @endif
    </li>
    <li id="page-right-nav" class="next">
        @if(isset($nextPage)&&$nextPage>0)
            <a page="{{ $nextPage }}" class="test_search_input_link hide-link" id="next-page" next_page="{{ $currentPage+1 }}"  href="/landing?page={{ $currentPage+1 }}{{{ $filters }}}">Next <span class="visuallyhidden">page</span>
                <span class="page-numbers">{{{ $currentPage }}} of {{{ $totalPages }}}</span></a>
        @endif
    </li>
</ul>

</div>