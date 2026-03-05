<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\DiscountUser;
use App\Models\SpecialOffer;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpecialOfferController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_marketing_special_offers");

        $user = auth()->user();
        $webinarsQuery = Webinar::query()->select('id')
            ->where(function ($qu) use ($user) {
                $qu->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })
            ->where('status', 'active');

        $webinarIds = $webinarsQuery->pluck('id')->toArray();

        $query = SpecialOffer::query()->whereIn('webinar_id', $webinarIds);

        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $webinars = $webinarsQuery->get();

        $data = [
            'pageTitle' => trans('panel.special_offers'),
            'webinars' => $webinars,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.marketing.special_offers.index', $data);
    }


    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $specialOffers = $query
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $specialOffers, $total, $count);
        }

        return [
            'specialOffers' => $specialOffers,
            'pagination' => $this->makePagination($request, $specialOffers, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $specialOffers, $total, $count)
    {
        $html = "";

        foreach($specialOffers as $specialOfferRow) {
            $html .= (string)view()->make('design_1.panel.marketing.special_offers.table_items', ['specialOffer' => $specialOfferRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $specialOffers, $total, $count, true)
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize("panel_marketing_special_offers");

        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required',
            'webinar_id' => 'required|exists:webinars,id',
            'percent' => 'required|numeric',
            'date_range' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $activeSpecialOfferForWebinar = Webinar::findOrFail($data["webinar_id"])->activeSpecialOffer();

        if ($activeSpecialOfferForWebinar) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('update.this_course_has_active_special_offer'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        $dateRange = explode('-', $data['date_range']);
        $fromDate = convertTimeToUTCzone($dateRange[0])->getTimestamp();
        $toDate = convertTimeToUTCzone($dateRange[1])->getTimestamp();

        SpecialOffer::create([
            'creator_id' => auth()->id(),
            'name' => $data["title"],
            'webinar_id' => $data["webinar_id"],
            'percent' => $data["percent"],
            'status' => SpecialOffer::$active,
            'created_at' => time(),
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ]);

        return response()->json([
            'code' => 200
        ], 200);
    }

    public function disable(Request $request, $id)
    {
        $user = auth()->user();

        $specialOffer = SpecialOffer::where('id', $id)->first();

        if (!empty($specialOffer)) {
            $course = $specialOffer->webinar;

            if ($course->isOwner($user->id)) {
                $specialOffer->update([
                    'status' => SpecialOffer::$inactive
                ]);

                return response()->json([
                    'code' => 200
                ], 200);
            }
        }

        return response()->json([], 422);
    }
}
