<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mixins\OpenAI\AiContentGenerator;
use App\Models\AiContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiContentController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize("panel_ai_contents_lists");

        $user = auth()->user();

        if ($user->checkAccessToAIContentFeature()) {

            $query = AiContent::query()->where('user_id', $user->id);
            $getListData = $this->getListsData($request, $query);

            if ($request->ajax()) {
                return $getListData;
            }

            $data = [
                'pageTitle' => trans('update.generated_contents'),
            ];
            $data = array_merge($data, $getListData);

            return view('design_1.panel.ai_contents.lists.index', $data);
        }

        abort(404);
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $contents = $query
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $contents, $total, $count);
        }

        return [
            'contents' => $contents,
            'pagination' => $this->makePagination($request, $contents, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $contents, $total, $count)
    {
        $html = "";

        foreach ($contents as $contentRow) {
            $html .= (string)view()->make('design_1.panel.ai_contents.lists.table_items', ['content' => $contentRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $contents, $total, $count, true)
        ]);
    }

    public function generate(Request $request)
    {
        $user = auth()->user();

        if ($user->checkAccessToAIContentFeature()) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'service_type' => 'required|in:text,image',
                'text_service_id' => 'required_if:service_type,text',
                'image_service_id' => 'required_if:service_type,image',
                'question' => 'required_if:text_service_id,custom_text',
                'image_size' => 'required_if:image_service_id,custom_image',
                'image_question' => 'required_if:image_service_id,custom_image',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $contentGenerator = new AiContentGenerator();
            $content = $contentGenerator->makeContent($user, $data);

            return response()->json([
                'code' => 200,
                'data' => $content
            ]);
        }

        return response()->json([], 422);
    }
}
