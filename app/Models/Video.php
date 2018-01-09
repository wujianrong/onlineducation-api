<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Video extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

    protected $revisionCreationsEnabled = true;/*记录增加动作*/

    /*记录删除动作*/
    protected $keepRevisionOf = array(
        'name',
        'status',
        'deleted_at'
    );

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    public $fillable = [ 'name', 'status', 'fileId', 'created_at', 'edk_key' ];

    public function section()
    {
        return $this->hasOne( Section::class );
    }

    public function video_urls()
    {
        return $this->hasMany( VideoUrl::class );
    }
}
