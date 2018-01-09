<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Zizaco\Entrust\Contracts\EntrustPermissionInterface;
use Zizaco\Entrust\Traits\EntrustPermissionTrait;

class Permission extends BaseModel implements EntrustPermissionInterface
{
    use SoftDeletes, EntrustPermissionTrait;
    use RevisionableTrait;

    public static function boot()
    {
        parent::boot();
    }

    protected $revisionCreationsEnabled = true;/*记录增加动作*/

    /*记录删除动作*/
    protected $keepRevisionOf = array(
        'name',
        'deleted_at'
    );

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    protected $fillable = [ 'name', 'parent_id', 'display_name' ];

    public static function allPermission()
    {
        return static::recent( 'permissions', [ 'roles' ] );
    }
}
