<?php

namespace App\Concerns;

trait WithCustomPagination
{
    public function paginationView(): string
    {
        return 'vendor.pagination.tailwind';
    }
}
