<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Zizaco\Entrust\Contracts\EntrustRoleInterface;
use Zizaco\Entrust\Traits\EntrustRoleTrait;

class Role extends BaseModel implements EntrustRoleInterface
{
    use RevisionableTrait;
    use EntrustRoleTrait {
        EntrustRoleTrait::restore insteadof SoftDeletes;
        EntrustRoleTrait::boot insteadof RevisionableTrait;
    }
    use SoftDeletes;


    public static function boot()
    {
        parent::boot();
    }

    protected $revisionEnabled = true;
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

    protected $fillable = [ 'name', 'display_name' ];

    /**
     * 角色用户
     */
    public function users()
    {
        return $this->belongsToMany( User::class );
    }

    /**
     * 角色菜单
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function menus()
    {
        return $this->belongsToMany( Menu::class );
    }


}
