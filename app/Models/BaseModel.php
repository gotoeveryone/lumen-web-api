<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * 登録時に日時を記録するカラム
     * 
     * @var string
     */
    const CREATED_AT = 'created';

    /**
     * 登録・更新時に日時を記録するカラム
     * 
     * @var string
     */
    const UPDATED_AT = 'modified';

    /**
     * 日付を変形する属性
     *
     * @var array
     */
    protected $dates = [self::CREATED_AT, self::UPDATED_AT];
}
