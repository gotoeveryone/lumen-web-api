<?php

namespace App\Listeners;

use Log;
use Mail;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\SendMail;
use App\Models\MailHistory;

/**
 * メール送信リスナー
 *
 * @author Kazuki_Kamizuru
 */
class SendMailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendMail  $event
     * @return void
     */
    public function handle(SendMail $event)
    {
        $user = $event->user;
        $history = $event->history;

        // メール送信
        Mail::raw($history->body, function(Message $m) use ($user, $history) {
            $m->from(env('MAIL_FROM_ADDRESS'), $user->name)
                ->subject($history->subject);

            $this->setAddress($m, $history, 'to');
            $this->setAddress($m, $history, 'cc');
            $this->setAddress($m, $history, 'bcc');
        });
        Log::info('ユーザ【'.$user->name.'】がメールを送信しました。');
    }

    /**
     * アドレスを指定の振り分け先へ設定します。
     *
     * @param Message $message
     * @param MailHistory $history
     * @param string $name "to"・"cc"・"bcc"
     * @return void
     */
    private function setAddress(Message $message, MailHistory $history, string $name)
    {
        $propertyName = 'address_'.$name;
        if (count($targets = explode(',', $history->$propertyName)) > 0) {
            foreach ($targets as $target) {
                if ($target) {
                    $message->$name($target);
                }
            }
        }
    }
}
