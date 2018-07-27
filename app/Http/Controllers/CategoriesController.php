<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;

class CategoriesController extends Controller
{
    //创建show方法
    public function show(Category $category){
    	//读取分类ID关联的话题，并按每20条分页
    	$topics = Topic::where('category_id',$category->id)->paginate(20);
    	//传递变量话题、分类到模板中去
    	return view('topics.index',compact('topics','category'));
    }
}
