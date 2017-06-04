<?php

namespace App\Events;

use App\Models\User;
use App\Models\MailHistory;

/**
 * メール送信イベント
 *
 * @author Kazuki_Kamizuru
 */
class SendMail extends Event
{
    /**
     * ユーザデータ
     *
     * @var User
     */
    public $user;    

    /**
     * メール送信データ
     *
     * @var MailHistory
     */
    public $history;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param MailHistory $history
     * @return void
     */
    public function __construct(User $user, MailHistory $history)
    {
        $this->user = $user;
        $this->history = $history;
    }
}
