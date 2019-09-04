<?php

namespace App\Models;

use Spatie\Permission\Models\Role as Model;

class Role extends Model
{
    const ADMIN = 'admin';
    const MEMBER = 'member';
    const ADMIN_ID = 1;
    const MEMBER_ID = 2;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name' ];
}
