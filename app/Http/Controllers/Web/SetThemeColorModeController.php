<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mixins\Financial\MultiCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class SetThemeColorModeController extends Controller
{
    public function setColorMode(Request $request)
    {
        $this->validate($request, [
            'mode' => 'required|in:dark,light'
        ]);

        $mode = $request->get('mode');

        if (auth()->check()) {
            $user = auth()->user();
            $user->update([
                'theme_color_mode' => $mode
            ]);
        }

        Cookie::queue('user_theme_color_mode', $mode, 30 * 24 * 60);

        return response()->json([
            'code' => 200,
        ]);
    }
}
