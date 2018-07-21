<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UsersController extends Controller
{
    //show 展示页面
    public function show(User $user){
    	return view('users.show',compact('user'));
    }
}
