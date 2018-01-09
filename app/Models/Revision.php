<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revision extends BaseModel
{
    public $fillable = [ 'revisionable_id', 'revisionable_type', 'key', 'user_id', 'old_value', 'new_value', 'guest' ];
}
