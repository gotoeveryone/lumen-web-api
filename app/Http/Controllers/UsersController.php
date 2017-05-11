<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

/**
 * ユーザコントローラ
 * 
 * @author Kazuki_Kamizuru
 */
class UsersController extends Controller
{
    /**
     * ユーザを取得
     *
     * @param Request $request
     * @return Response
     */
    public function users(Request $request)
    {
        $data = $request->json();
        if (!($user = User::where('is_active', true)->find($data->user_id))) {
            return response('', 404);
        }

        return response([
            'userId' => $user->account,
            'userName' => $user->name,
            'sex' => $user->sex,
            'mailAddress' => $user->mail_address,
            'role' => $user->role,
        ], 200);
    }
}
