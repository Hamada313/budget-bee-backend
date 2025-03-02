<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends BaseModel
{
    //
    use SoftDeletes ;
    protected $fillable = ['user_id', 'name', 'balance'];
    
}
