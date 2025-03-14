<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;


/* 
   eloquent orm for account can use this class because it 
   it is the singular form of the table name in the database
   so it will automatically connect to the accounts table in the database   
   and that allows it to perform all the methods of the eloquent model class 
   that is extended from BaseModel custom class 
   But if you want to use a different table name you can use the protected $table property  
   and set the table name to the name of the table in the database  
   like protected $table = 'table_name';    
*/
class Account extends BaseModel
{
    //
    use SoftDeletes ;
    protected $fillable = ['user_id', 'name', 'balance','is_default'];
    
}
