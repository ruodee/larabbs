<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //在用户模型中增加与话题模型的关联

    public function topics(){
        return $this->hasMany(Topic::class);
    }
    //模型权限验证 适合任何模型，包括Topic,验证用户是否具有传入$model模型的操作权限
    public function isAuthorOf($model){
        if(isset($model->user_id))
        return $this->id == $model->user_id;
        else
            return false;
    }
}
