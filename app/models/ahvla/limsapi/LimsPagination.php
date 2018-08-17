<?php

namespace ahvla\limsapi;

use ahvla\entity\submission\Submission;
use Illuminate\Support\Facades\Paginator;

class LimsPagination
{
    /*
     * All the records before pagination
     */
    public $totalItems=[];

    /*
     * Total number of records before pagination
     */
    public $totalItemsCount=0;

    /*
     * The current records on current page
     */
    public $currentItems=[];

    /*
     * Number of records per page
     */
    public $perPage=1;

    /*
     * Current page
     */
    public $page=1;

    /*
     * Laravel paginator
     */
    public $paginator;

    public function __construct($items, $perPage, $page)
    {
        $this->totalItems = $items;
        $this->totalItemsCount = count($items);
        $this->perPage = $perPage;
        $this->page = $page;
    }

    public function setItems($items)
    {
        $this->totalItems = $items;
        $this->totalItemsCount = count($items);
    }

    public function paginate()
    {
        $offset = ($this->page * $this->perPage) - $this->perPage;

        $this->currentItems = array_slice($this->totalItems, $offset, $this->perPage, true);

        $this->paginator = Paginator::make($this->currentItems, count($this->currentItems), $this->perPage);

        return $this->paginator;
    }

    public function previousPage()
    {
        if ($this->page > 1) {
            return $this->page-1;
        }

        return 0;
    }

    public function nextPage()
    {
        if ($this->page < $this->totalPages()) {
            return $this->page+1;
        }

        return 0;
    }

    public function totalPages()
    {
        if ( ($this->perPage>0) && (count($this->totalItems)>0) ) {
            return ceil( count($this->totalItems) / $this->perPage );
        }

        return 0;
    }

}