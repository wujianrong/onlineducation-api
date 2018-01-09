<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends BaseModel
{
    use RevisionableTrait;
    use EntrustUserTrait {
        EntrustUserTrait::restore insteadof SoftDeletes;
        EntrustUserTrait::boot insteadof RevisionableTrait;
    }
    use SoftDeletes;

    protected $revisionCreationsEnabled = true;/*记录增加动作*/

    /*记录删除动作*/
    protected $keepRevisionOf = array(
        'name',
        'nickname',
        'frozen',
        'auth_password_id',
        'deleted_at'
    );

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
        'name', 'auth_password_id', 'nickname', 'gender', 'creater_id', 'frozen'
    ];

    protected $casts = [
        'gender' => 'boolean',
    ];

    public function isSuperAdmin()
    {
        return $this->hasRole( 'super_admin' );
    }

    /**
     * 用户角色
     */
    public function roles()
    {
        return $this->belongsToMany( Role::class );
    }

    public function auth_password()
    {
        return $this->belongsTo( AuthPassword::class );
    }

    /*冻结账号*/
    public function scopeFrozen()
    {
        if( !$this->frozen ){
            $this->frozen = 1;
            $this->update();
        }

    }

    /*解冻账号*/
    public function scopeRefrozen()
    {
        if( $this->frozen ){
            $this->frozen = 0;
            $this->update();
        }
    }
}

