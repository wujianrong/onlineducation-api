<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Lesson extends BaseModel
{
    use SoftDeletes, RevisionableTrait;

    protected $revisionCreationsEnabled = true;/*记录增加动作*/

    /*记录动作*/
    protected $keepRevisionOf = array(
        'name',
        'title',
        'type',
        'nav_id',
        'teacher_id',
        'educational_id',
        'status',
        'pictrue',
        'price',
        'learning',
        'for',
        'is_top',
        'is_nav',
        'describle',
        'deleted_at'
    );

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    public $fillable = [
        'name',
        'title',
        'type',
        'nav_id',
        'teacher_id',
        'educational_id',
        'status',
        'pictrue',
        'price',
        'like',
        'is_top',
        'is_nav',
        'learning',
        'for',
        'describle',
        'play_times'
    ];

    public function genres()
    {
        return $this->belongsToMany( Genre::class );
    }

    public function nav()
    {
        return $this->belongsTo( Nav::class );
    }

    public function teacher()
    {
        return $this->belongsTo( Teacher::class );
    }

    public function educational()
    {
        return $this->belongsTo( Educational::class );
    }

    public function sections()
    {
        return $this->hasMany( Section::class );
    }

    public function discusses()
    {
        return $this->hasMany( Discusse::class );
    }

    /*购买者*/
    public function pay_guests()
    {
        return $this->belongsToMany( Guest::class )->wherePivotIn( 'type', [ 2, 4, 5, 6 ] )->withPivot( [ 'type', 'sections', 'add_date','collect_date' ] );
    }

    /*课程的用户*/
    public function guests()
    {
        return $this->belongsToMany( Guest::class )->withPivot( [ 'type', 'sections', 'add_date','collect_date' ] );
    }

    /*课程学员*/
    public function students()
    {
        return $this->belongsToMany( Guest::class )->wherePivot( 'sections', '!=', '' )->withPivot( [ 'type', 'sections', 'add_date','collect_date' ] )->with( 'lessons' );
    }

}
