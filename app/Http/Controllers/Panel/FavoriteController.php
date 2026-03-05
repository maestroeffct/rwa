<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Favorite::query()->where('user_id', $user->id)
            ->whereHas('webinar');

        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }


        $data = [
            'pageTitle' => trans('panel.favorites'),
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.webinars.favorites.index', $data);
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $favorites = $query
            ->with([
                'webinar' => function ($query) {
                    $query->with([
                        'teacher' => function ($qu) {
                            $qu->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'mobile', 'email');
                        },
                        'category'
                    ]);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $favorites, $total, $count);
        }

        return [
            'favorites' => $favorites,
            'pagination' => $this->makePagination($request, $favorites, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $favorites, $total, $count)
    {
        $html = "";

        foreach ($favorites as $favoriteRow) {
            $html .= '<div class="col-12 col-md-6 col-lg-3 col-xl-2 mt-20">';
            $html .= (string)view()->make("design_1.panel.webinars.favorites.grid_card", ['favorite' => $favoriteRow]);
            $html .= '</div>';
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $favorites, $total, $count, true)
        ]);
    }


    public function destroy($id)
    {
        $user = auth()->user();

        $favorite = favorite::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($favorite)) {
            $favorite->delete();

            return response()->json([
                'code' => 200
            ], 200);
        }

        return response()->json([], 422);
    }
}
