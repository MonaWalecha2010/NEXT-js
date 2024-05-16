<?php

namespace App\Traits;

trait Paginatable {


    /**
     * @return int
     */
    public function getPerPage()
    {
        $pageSize = (int) request('page_size', config('jetworks145.per_page',15) );
        return min($pageSize, $this->getPageSizeLimit());
    }

    /**
     * @return int
     */
    public function getPageSizeLimit()
    {
        return (int) config('jetworks145.per_page_max',100);
    }

}
