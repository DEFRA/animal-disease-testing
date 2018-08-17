<div class="row" style="padding-top: 20px">
    <h2 class="js-dont-need-advice">Tests</h2>
    <h2 class="js-need-advice" style="display: none">Recommended tests</h2>
    <p id="total-tests-count" @if(isset($totalItems)&&$totalItems>=0) style="display: block;" @else style="display: none;" @endif>
        <span class="counter">{{{ $totalItems }}}</span> tests found
    </p>

    <div id="testSearchResults" @if(!count($searchResults)) style="display: none" @endif>
        @if(!count($searchResults))
            @include('submission.steps.partials.tests.result-templates.test-template')
        @endif
        @foreach($searchResults as $index=>$testRow)
            @include('submission.steps.partials.tests.result-templates.test-template', ['testRow'=>$testRow])
        @endforeach
    </div>

    <div id="testAdviceSearchResults" @if(!count($adviceSearchResults)) style="display: none" @endif>
        @if(count($adviceSearchResults))

            @include('submission.steps.partials.tests.test-recommendations-template', ['adviceSearchResults'=>$adviceSearchResults])

        @endif

    </div>
    <ul class="previous-next-navigation">
        <li id="page-left-nav" class="previous">
            @if(isset($previousPage)&&$previousPage>0)
                <a id="page-{{{ $currentPage-1 }}}" page="{{{ $previousPage }}}" class="test_search_input_link hide-link"
                   href="/step4?page={{{ $currentPage-1 }}}">Previous <span class="visuallyhidden">page</span>
                    <span class="page-numbers">{{{ $currentPage }}} of {{{ $totalPages }}}</span>
                </a>
            @endif
        </li>
        <li id="page-right-nav" class="next">
            @if(isset($nextPage)&&$nextPage>0)
                <a id="page-{{{ $currentPage+1 }}}" page="{{{ $nextPage }}}" class="test_search_input_link hide-link"
                   href="/step4?page={{{ $currentPage+1 }}}">Next <span class="visuallyhidden">page</span>
                    <span class="page-numbers">{{{ $currentPage }}} of {{{ $totalPages }}}</span></a>
            @endif
        </li>
    </ul>
</div>