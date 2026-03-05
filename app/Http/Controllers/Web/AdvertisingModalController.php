<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdvertisingModalController extends Controller
{

    public function getModal(Request $request)
    {
        $advertisingModalSettings = getAdvertisingModalSettings(true);

        $data = [
            'advertisingModalSettings' => $advertisingModalSettings,
        ];

        $html = (string)view()->make("design_1.web.includes.advertise_modal.modal", $data);

        return response()->json([
            'code' => 200,
            'html' => $html,
        ]);
    }
}
