<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\EventTicketSold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventTicketValidationController extends Controller
{
    public function index(Request $request)
    {
        $getSeoMetas = getSeoMetas('event_ticket_validation');
        $pageTitle = !empty($getSeoMetas['title']) ? $getSeoMetas['title'] : trans('update.event_ticket_validation_page_title');
        $pageDescription = !empty($getSeoMetas['description']) ? $getSeoMetas['description'] : trans('update.event_ticket_validation_page_title');
        $pageRobot = getPageRobot('event_ticket_validation');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
        ];

        return view('design_1.web.events.ticket_validation.index', $data);
    }

    public function checkValidate(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'ticket_code' => 'required|string',
            'captcha' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $code = $data['ticket_code'];
        $purchasedTicket = EventTicketSold::query()->where('code', $code)
            ->with([
                'sale',
                'eventTicket',
                'user' => function ($query) {
                    $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'mobile', 'email');
                },
            ])
            ->first();

        $result = [];

        if (!empty($purchasedTicket) and !empty($purchasedTicket->eventTicket)) {
            $result = [
                'purchasedTicket' => $purchasedTicket,
                'eventTicket' => $purchasedTicket->eventTicket,
                'event' => $purchasedTicket->eventTicket->event,
            ];
        }

        $html = (string)view()->make('design_1.web.events.ticket_validation.status', $result);

        return response()->json([
            'code' => 200,
            'html' => $html
        ]);
    }
}
