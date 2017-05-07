<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

/**
 * 監視用コントローラ
 * 
 * @author Kazuki_Kamizuru
 */
class StatesController extends Controller
{
    /**
     * サービスの状態を返す。
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return response([
            'status' => 'OK',
            'environment' => env('APP_ENV'),
            'logLevel' => env('APP_LOG_LEVEL'),
            'timeZone' => Carbon::now()->getTimezone()->getName(),
        ], 200);
    }
}
