<?php

namespace App\Models;

class MailHistory extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'address_to', 'address_cc', 'address_bcc', 'subject', 'body',
    ];
}
