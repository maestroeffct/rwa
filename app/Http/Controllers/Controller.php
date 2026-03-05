<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainTraits\FilesTraits;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use FilesTraits;

    public $perPage = 10;



    public function makePagination(Request $request, $items, $total, $perPage, $justHtml = false, $customPathInfo = null)
    {
        $currentPage = $request->get('page') ?? 1;

        $pagination = new Paginator($items, $total, $perPage, $currentPage, [
            'path' => $customPathInfo ? $customPathInfo : $request->getPathInfo(),
            'query' => $request->query()
        ]);

        if ($justHtml) {
            return $this->makePaginationHtml($pagination);
        }

        return $pagination;
    }

    public function makePaginationHtml($paginate)
    {
        return (string)$paginate->links('vendor.pagination.design_1');
    }
}
