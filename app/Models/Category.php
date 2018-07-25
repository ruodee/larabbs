<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //模型保存到数据库的可
    protected $fillable = ['name','description'];
}
