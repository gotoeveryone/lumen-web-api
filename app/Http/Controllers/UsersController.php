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
     * @param int $id
     * @return Response
     */
    public function users(Request $request, int $id)
    {
        if (!($user = User::where('is_active', true)->find($id))) {
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
