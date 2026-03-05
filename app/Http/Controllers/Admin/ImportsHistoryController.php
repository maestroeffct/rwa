<?php

namespace App\Http\Controllers\Admin;


use App\Exports\BulkImportsHistoriesExport;
use App\Http\Controllers\Controller;
use App\Models\BulkImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportsHistoryController extends Controller
{


    public function index(Request $request)
    {
        $this->authorize("admin_imports_from_csv_history");

        $query = BulkImport::query();
        $query->whereHas('user');
        $query->orderBy('created_at', 'desc');

        $topStats = $this->getPageTopStats(deepClone($query));

        $query = $this->handleFilters($request, $query);
        $query->with([
            'user' => function ($query) {
                $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'email', 'mobile');
            }
        ]);
        $imports = $query->paginate($this->perPage);

        $data = [
            'pageTitle' => trans('update.bulk_imports'),
            'imports' => $imports,
        ];
        $data = array_merge($data, $topStats);

        return view('admin.imports.history.index', $data);
    }

    private function getPageTopStats($query): array
    {
        $totalImports = deepClone($query)->count();
        $validRecords = deepClone($query)->sum('valid_items');
        $invalidRecords = deepClone($query)->sum('invalid_items');
        $totalRecords = $validRecords + $invalidRecords;

        return [
            'totalImports' => $totalImports,
            'totalRecords' => $totalRecords,
            'validRecords' => $validRecords,
            'invalidRecords' => $invalidRecords,
        ];
    }

    private function handleFilters(Request $request, $query)
    {
        $data_type = $request->get('data_type');
        $user_ids = $request->get('user_ids');
        $import_date = $request->get('import_date');


        if (!empty($data_type) and $data_type != "all") {
            $query->where('data_type', $data_type);
        }

        if (!empty($user_ids)) {
            if (!is_array($user_ids)) {
                $user_ids = [$user_ids];
            }

            $query->whereIn('user_id', $user_ids);
        }

        if (!empty($import_date)) {
            $date = strtotime($import_date);

            $startOfDay = startOfDayTimestamp($date);
            $endOfDay = endOfDayTimestamp($date);


            $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
        }

        return $query;
    }

    public function delete($id)
    {
        $this->authorize("admin_imports_from_csv_history");

        $import = BulkImport::query()->findOrFail($id);

        $import->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans("update.import_record_deleted_successful"),
            'status' => 'success'
        ];

        return redirect(getAdminPanelUrl("/imports/history"))->with(['toast' => $toastData]);
    }

    public function exportExcel(Request $request)
    {
        $this->authorize("admin_imports_from_csv_history");

        $query = BulkImport::query();
        $query->whereHas('user');
        $query->orderBy('created_at', 'desc');
        $query = $this->handleFilters($request, $query);
        $query->with([
            'user' => function ($query) {
                $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'email', 'mobile');
            }
        ]);
        $imports = $query->get();

        $export = new BulkImportsHistoriesExport($imports);
        return Excel::download($export, 'imports_history.xlsx');
    }
}
