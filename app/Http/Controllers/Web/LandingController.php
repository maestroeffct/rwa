<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Landing;
use Illuminate\Http\Request;

class LandingController extends Controller
{

    public function index(Request $request, $landingUrl)
    {
        $landingItem = Landing::query()->where('url', $landingUrl)
            ->where('enable', true)
            ->with([
                'components' => function ($query) {
                    $query->with([
                        'landingBuilderComponent'
                    ]);
                    $query->orderBy('order', 'asc');
                }
            ])
            ->first();

        if (!empty($landingItem)) {
            $data = [
                'landingItem' => $landingItem,
                'pageTitle' => $landingItem->title,
            ];

            return view('landingBuilder.front.landing.index', $data);
        }

        abort(404);
    }
}
