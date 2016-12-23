<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    protected $table = 'update';
    protected $fillable = ['version','created_at','updated_at'];

}
