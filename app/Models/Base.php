<?php
namespace App\Models;

use Auth;
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
     * Set the value of the "created at" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setCreatedAt($value)
    {
        $this->created_by = Auth::user()->getUserId();
        return parent::setCreatedAt($value);
    }

    /**
     * Set the value of the "updated at" attribute.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function setUpdatedAt($value)
    {
        $this->modified_by = Auth::user()->getUserId();
        return parent::setUpdatedAt($value);
    }
}
