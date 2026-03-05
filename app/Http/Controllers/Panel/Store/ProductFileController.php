<?php

namespace App\Http\Controllers\Panel\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductFile;
use App\Models\Translation\ProductFileTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductFileController extends Controller
{

    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->get('ajax')['new'];
        $fileUpload = $request->file('ajax.new.file_upload');

        if (!empty($fileUpload)) {
            $data['file_upload'] = $fileUpload;
        }

        if (empty($data['storage'])) {
            $data['storage'] = 'upload';
        }

        $rules = [
            'product_id' => 'required',
            'title' => 'required|max:255',
            'description' => 'required',
            'file_upload' => $this->handleUploadFileValidationByType($data['file_type'] ?? null, true),
            'file_type' => 'required',
            'volume' => 'required',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $product = Product::where('id', $data['product_id'])
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($product)) {

            $path = $this->uploadFile($fileUpload, "products/{$product->id}/files", null, $product->creator_id);

            $file = ProductFile::create([
                'creator_id' => $user->id,
                'product_id' => $data['product_id'],
                'path' => $path,
                'order' => null,
                'volume' => $data['volume'],
                'file_type' => $data['file_type'],
                'online_viewer' => (!empty($data['online_viewer']) and $data['online_viewer'] == 'on'),
                'status' => (!empty($data['status']) and $data['status'] == 'on') ? ProductFile::$Active : ProductFile::$Inactive,
                'created_at' => time(),
            ]);

            if (!empty($file)) {
                $locale = $request->get('locale', getDefaultLocale());

                ProductFileTranslation::updateOrCreate([
                    'product_file_id' => $file->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);
            }

            return response()->json([
                'code' => 200,
            ], 200);
        }

        abort(403);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $data = $request->get('ajax')[$id];

        $product = Product::where('id', $data['product_id'])
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($product)) {
            $file = ProductFile::where('id', $id)
                ->where('creator_id', $user->id)
                ->where('product_id', $product->id)
                ->first();

            if (!empty($file)) {

                $fileUpload = $request->file("ajax.{$id}.file_upload");

                if (!empty($fileUpload)) {
                    $data['file_upload'] = $fileUpload;
                }

                $fileTypeIsChanged = !!(empty($data['file_type']) or $data['file_type'] != $file->file_type);

                $rules = [
                    'product_id' => 'required',
                    'title' => 'required|max:255',
                    'description' => 'required',
                    'file_upload' => $this->handleUploadFileValidationByType($data['file_type'] ?? null, $fileTypeIsChanged),
                    'file_type' => 'required',
                    'volume' => 'required',
                ];

                $validator = Validator::make($data, $rules);

                if ($validator->fails()) {
                    return response([
                        'code' => 422,
                        'errors' => $validator->errors(),
                    ], 422);
                }


                $path = $file->path;

                if (!empty($fileUpload)) {
                    $path = $this->uploadFile($fileUpload, "products/{$product->id}/files", null, $product->creator_id);
                }

                $file->update([
                    'path' => $path,
                    'volume' => $data['volume'],
                    'file_type' => $data['file_type'],
                    'online_viewer' => (!empty($data['online_viewer']) and $data['online_viewer'] == 'on'),
                    'status' => (!empty($data['status']) and $data['status'] == 'on') ? ProductFile::$Active : ProductFile::$Inactive,
                    'created_at' => time(),
                ]);

                $locale = $request->get('locale', getDefaultLocale());

                ProductFileTranslation::updateOrCreate([
                    'product_file_id' => $file->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);

                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }

        abort(403);
    }

    private function handleUploadFileValidationByType($fileType = null, $required = true)
    {
        $rule = ($required ? 'required' : 'nullable') . '|file|max:2097152'; // 2GB max size

        if (!empty($fileType)) {
            switch ($fileType) {
                case 'pdf':
                    $rule .= '|mimes:pdf';
                    break;
                case 'power_point':
                    $rule .= '|mimes:ppt,pptx';
                    break;
                case 'sound':
                    $rule .= '|mimes:mp3,wav,ogg,aac';
                    break;
                case 'video':
                    $rule .= '|mimes:mp4,avi,mkv,mov,wmv,flv,webm';
                    break;
                case 'image':
                    $rule .= '|mimes:jpg,jpeg,png,gif,bmp,webp,svg';
                    break;
                case 'archive':
                    $rule .= '|mimes:zip,rar,tar,gz,7z';
                    break;
                case 'document':
                    $rule .= '|mimes:doc,docx,xls,xlsx,csv,txt,rtf';
                    break;
                case 'project':
                    $rule .= '';
                    break;
            }
        }

        return $rule;
    }


    public function destroy(Request $request, $id)
    {
        $file = ProductFile::where('id', $id)
            ->where('creator_id', auth()->id())
            ->first();

        if (!empty($file)) {
            $path = $file->path;

            $file->delete();

            $this->removeFile($path);
        }

        return response()->json([
            'code' => 200
        ], 200);
    }

    public function orderItems(Request $request)
    {
        $user = auth()->user();
        $data = $request->all();

        $validator = Validator::make($data, [
            'items' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $itemIds = explode(',', $data['items']);

        if (!is_array($itemIds) and !empty($itemIds)) {
            $itemIds = [$itemIds];
        }

        if (!empty($itemIds) and is_array($itemIds) and count($itemIds)) {
            foreach ($itemIds as $order => $id) {
                ProductFile::where('id', $id)
                    ->where('creator_id', $user->id)
                    ->update(['order' => ($order + 1)]);
            }
        }

        return response()->json([
            'code' => 200,
        ], 200);
    }

    public function download($id)
    {
        $file = ProductFile::where('id', $id)->first();
        if (!empty($file)) {
            $product = Product::where('id', $file->product_id)
                ->where('status', Product::$active)
                ->first();

            if (!empty($product) and $product->checkUserHasBought()) {
                $fileType = explode('.', $file->path);
                $fileType = end($fileType);

                $filePath = public_path($file->path);

                if (file_exists($filePath)) {
                    $fileName = str_replace([' ', '.'], '-', $file->title);
                    $fileName .= '.' . $fileType;

                    $headers = [
                        'Content-Type: application/' . $fileType,
                    ];

                    return response()->download($filePath, $fileName, $headers);
                }
            }
        }

        $toastData = [
            'title' => trans('public.not_access_toast_lang'),
            'msg' => trans('public.not_access_toast_msg_lang'),
            'status' => 'error'
        ];
        return back()->with(['toast' => $toastData]);
    }
}
