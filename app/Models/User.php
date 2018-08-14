<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Traits\ActiveUserHelper;
    use HasRoles;
    use Notifiable { notify as protected laravelNotify;}
    //记录用户最后登录时间
    use Traits\LastActivedAtHelper;
    public function notify($instance){
        //如果要通知的是当前用户，也就是$thi->id==Auth::id();就没有必要通知了
        if($this->id == Auth::id()){
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);

    }

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

    public function replies(){
        return $this->hasMany(Reply::class);
    }

    public function markAsRead(){
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
    //password属性的模型修改器，发生在写入数据库前

    public function setPasswordAttribute($value){
        if(strlen($value)!=60)
            $value = bcrypt($value);
        $this->attributes['password'] = $value;

    }
    //avatar用户头像模型的修改器，拼接URL

    public function setAvatarAttribute($path){
        //如果不是'http'子串开头，那就是从管理后台上传的，需要补全URL
        if(!starts_with($path,'http')){
            //拼接完整的URL
            $path = config('app.url')."/uploads/images/avatars/$path";
        }
        $this->attributes['avatar'] = $path;
    }

    

}
