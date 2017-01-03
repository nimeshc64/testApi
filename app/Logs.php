<?php
/**
 * Created by PhpStorm.
 * User: Nimesh
 * Date: 1/3/2017
 * Time: 10:13 AM
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $table = 'logs';
    protected $fillable = ['branch','terminal','pullNow','lastFileName','created_at','updated_at'];
}
