<?php

namespace App\Http\Controllers;

use Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\SendMail;
use App\Models\User;
use App\Models\MailHistory;

/**
 * メールコントローラ
 * 
 * @author Kazuki_Kamizuru
 */
class MailController extends Controller
{
    /**
     * ユーザを取得
     *
     * @param SendMailRequest $request
     * @return Response
     */
    public function sendmail(Request $request)
    {
        $this->validate($request, [
            'to' => 'required|max:500',
            'cc' => 'max:500',
            'bcc' => 'max:500',
            'subject' => 'required|max:50',
            'body' => 'required|max:500',
        ]);

        // ユーザを取得
        $user = User::getActiveUser($request->get('user_id'));

        // データを登録
        $history = MailHistory::create([
            'user_id' => $user->id,
            'address_to' => $request->get('to'),
            'address_cc' => $request->get('cc'),
            'address_bcc' => $request->get('bcc'),
            'subject' => $request->get('subject'),
            'body' => $request->get('body'),
        ]);

        // メール送信のイベントを発火
        Event::fire(new SendMail($user, $history));

        return response([
            'code' => 200,
            'message' => 'Mail sended.',
        ], 200);
    }
}
