<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;
use App\Models\User;
use App\Models\Link;
class CategoriesController extends Controller
{
    //创建show方法
    public function show(Category $category,Request $request,Topic $topic,User $user,Link $link){
    	//读取分类ID关联的话题，并按每20条分页
    	$topics = $topic->withOrder($request->order)->where('category_id',$category->id)->paginate(20);
    	$active_users = $user->getActiveUsers();
    	$links = $link->getAllCached();
    	//传递变量话题、分类到模板中去
    	return view('topics.index',compact('topics','category','active_users','links'));
    }
}
