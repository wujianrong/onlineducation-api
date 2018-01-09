<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class Setting extends BaseModel
{
    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;

    protected $keepRevisionOf = [
        'index_type',
        'index_count',
        'vip_send_seting',
        'wechat_sub',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'index_type',
        'index_count',
        'vip_send_seting',
        'wechat_sub',
    ];

}
