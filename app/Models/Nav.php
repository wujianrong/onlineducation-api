<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Nav extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

    protected $revisionCreationsEnabled = true;/*记录增加动作*/

    /*记录删除动作*/
    protected $keepRevisionOf = array(
        'name',
        'pictrue',
        'order_type',
        'deleted_at'
    );

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    public $fillable = [ 'name', 'pictrue', 'order_type' ];

    public function lessons()
    {
        return $this->hasMany( Lesson::class );
    }
}
