<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Mixins\BulkImports\ImportManager;
use App\Models\BulkImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;

class ImportsController extends Controller
{

    protected $validItemsSessionName = "valid_items_session";
    protected $invalidItemsCountSessionName = "invalid_items_count_session";


    public function index(Request $request)
    {
        $this->authorize("admin_imports_from_csv");

        session()->forget($this->validItemsSessionName);
        session()->forget($this->invalidItemsCountSessionName);

        $data = [
            'pageTitle' => trans('update.bulk_imports'),
        ];

        return view('admin.imports.index', $data);
    }

    public function validation(Request $request)
    {
        $this->authorize("admin_imports_from_csv");

        $this->validate($request, [
            'type' => 'required|in:courses,categories,users,products',
            'csv_file' => 'required|file',
            'locale' => 'required_if:type,courses,products,categories',
            'currency' => 'required_if:type,courses,products',
        ]);

        $data = $request->all();

        $type = $data['type'];
        $path = $request->file('csv_file')->getRealPath();

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $validatedItems = [];
        $errorsItems = [];
        $errors = [];

        $duplicateRowsLogs = [];

        $importChannel = ImportManager::makeChannel($type);

        foreach ($records as $index => $row) {
            $rules = $importChannel->getValidatorRule();
            $validator = Validator::make($row, $rules);

            if ($validator->fails()) {
                $errorsItems[] = $row;
                $errors[] = $validator->errors()->toArray();
            } else {

                // duplicate Errors
                if ($type == "users") {
                    $importChannel->checkDuplicateRows($duplicateRowsLogs, $row,$index);
                }

                if (!empty($duplicateRowsLogs['errors']) and !empty($duplicateRowsLogs['errors'][$index])) {
                    $errorsItems[] = $row;
                    $errors[] = $duplicateRowsLogs['errors'][$index];
                } else {
                    $validatedItems[] = $row;
                }
            }
        }

        if (count($validatedItems)) {
            session()->put($this->validItemsSessionName, $validatedItems);
        }

        session()->put($this->invalidItemsCountSessionName, count($errorsItems));


        $data['pageTitle'] = trans('update.import_data_validation');
        $data['validatedItems'] = $validatedItems;
        $data['errorsItems'] = $errorsItems;
        $data['errors'] = $errors;
        $data['tableHeaders'] = $csv->getHeader();

        return view('admin.imports.validation.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("admin_imports_from_csv");

        $this->validate($request, [
            'type' => 'required|in:courses,categories,users,products',
            'locale' => 'required_if:type,courses,products,categories',
            'currency' => 'required_if:type,courses,products',
        ]);

        $data = $request->all();

        $validItems = session()->get($this->validItemsSessionName);
        $invalidItemsCount = session()->get($this->invalidItemsCountSessionName) ?? 0;

        if (!empty($validItems) and count($validItems)) {
            $type = $data['type'];
            $locale = $data['locale'] ?? null;
            $currency = $data['currency'] ?? null;

            $importChannel = ImportManager::makeChannel($type);
            $importChannel->import($validItems, $locale, $currency);

            BulkImport::query()->create([
                'user_id' => auth()->id(),
                'data_type' => $type,
                'valid_items' => count($validItems),
                'invalid_items' => $invalidItemsCount,
                'created_at' => time(),
            ]);

            $toastData = [
                'title' => trans('update.data_imported_successfully'),
                'msg' => trans('update.valid_record_imported_msg', ['count' => count($validItems), 'item' => trans($type)]),
                'status' => 'success'
            ];
            return redirect(getAdminPanelUrl("/imports/history"))->with(['toast' => $toastData]);
        }

        $toastData = [
            'title' => trans('public.request_failed'),
            'msg' => trans('update.there_is_no_valid_record_to_import'),
            'status' => 'error'
        ];
        return redirect(getAdminPanelUrl("/imports"))->with(['toast' => $toastData]);
    }

    public function downloadSample(Request $request)
    {
        $type = $request->get('type');

        if (!empty($type) and in_array($type, ['courses', 'categories', 'users', 'products'])) {
            $filePath = public_path("/vendor/imports-csv-sample-files/{$type}.csv");

            if (file_exists($filePath)) {
                $fileName = "{$type}.csv";

                $headers = array(
                    'Content-Type: text/csv',
                );

                return response()->download($filePath, $fileName, $headers);
            }
        }

        abort(404);
    }

}
