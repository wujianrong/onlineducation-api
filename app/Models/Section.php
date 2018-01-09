<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Section extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

    protected $revisionCreationsEnabled = true;

    protected $keepRevisionOf = [
        'name',
        'lesson_id',
        'video_id',
        'is_free',
        'deleted_at'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lesson_id', 'video_id', 'is_free', 'play_times',
    ];

    public function lesson()
    {
        return $this->belongsTo( Lesson::class );
    }

    public function video()
    {
        return $this->belongsTo( Video::class );
    }

}
