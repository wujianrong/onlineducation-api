<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Venturecraft\Revisionable\RevisionableTrait;

class Guest extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, Notifiable, RevisionableTrait;

    protected $revisionCreationsEnabled = true;/*记录增加动作*/

    /*记录删除动作*/
    protected $keepRevisionOf = array(
        'phone',
        'vip_id',
        'deleted_at'
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'gender', 'openid', 'picture', 'vip_id', 'auth_password_id', 'position', 'phone'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    /*标签*/
    public function labels()
    {
        return $this->belongsToMany( Label::class );
    }

    /*设置标签*/
    public function setLabel( array $label_ids )
    {
        $this->labels()->sync( $label_ids );
    }

    /*评论*/
    public function discusses()
    {
        return $this->hasMany( Discusse::class );
    }

    /*订单*/
    public function orders()
    {
        return $this->hasMany( Order::class );
    }

    /*订单*/
    public function lessoon_orders()
    {
        return $this->hasMany( Order::class )->where( 'type', 1 );
    }

    /*订单*/
    public function vip_orders()
    {
        return $this->hasMany( Order::class )->where( 'type', 2 );
    }

    /*消息*/
    public function messages()
    {
        return $this->belongsToMany( Message::class );
    }

    /*vip*/
    public function vip()
    {
        return $this->belongsTo( Vip::class );
    }

    //所有课程
    public function lessons()
    {
        return $this->belongsToMany( Lesson::class )->withPivot( [ 'type', 'sections', 'add_date','collect_date' ] );
    }

    //点赞课程
    public function like_lessons()
    {
        return $this->belongsToMany( Lesson::class )->wherePivotIn( 'type', [ 0, 3, 4, 6 ] )->withPivot( [ 'type', 'sections', 'add_date','collect_date' ] );
    }

    //收藏课程
    public function collect_lessons()
    {
        return $this->belongsToMany( Lesson::class )->wherePivotIn( 'type', [ 1, 3, 5, 6 ] )->orderBy( 'collect_date', 'desc' )->withPivot( [ 'type', 'sections', 'add_date','collect_date' ] );
    }

    //购买课程
    public function pay_lessons()
    {
        return $this->belongsToMany( Lesson::class )->wherePivotIn( 'type', [ 2, 4, 5, 6 ] )->withPivot( [ 'type', 'sections', 'add_date','collect_date' ] );
    }

    //学习课程
    public function learned_lessons()
    {
        return $this->belongsToMany( Lesson::class )->wherePivot( 'sections', '!=', '' )->orderBy( 'add_date', 'desc' )->withPivot( [ 'type', 'sections', 'add_date','collect_date' ] );
    }

    /*认证表*/
    public function auth_password()
    {
        return $this->belongsTo( AuthPassword::class );
    }
}
