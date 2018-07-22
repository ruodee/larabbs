<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;
class UsersController extends Controller
{
    //show 展示页面
    public function show(User $user){
    	return view('users.show',compact('user'));
    }
    //edit 展示用户信息编辑页面
    public function edit(User $user){
    	return view('users.edit',compact('user'));
    }
    //update 用户更新个人信息
    public function update(UserRequest $request,ImageUploadHandler $uploader,User $user){
        $data = $request->all();

        if($request->avatar){
            $result = $uploader->save($request->avatar,'avatars',$user->id,362);
            if($result){
                $data['avatar'] = $result['path']; 
            }
        }
    	$user->update($data);
    	return redirect()->route('users.show',$user->id)->with('success','个人信息更新成功！');
    }
}
