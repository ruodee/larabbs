<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app("Dingo\Api\Routing\Router");

$api->version('v1',[
	'namespace' => 'App\Http\Controllers\Api',
	'middleware' => 'serializer:array'
	],function($api){
		$api->group([
			'middleware' => 'api.throttle',
			'limit' => config('api.rate_limits.sign.limit'),
			'expires' => config('api.rate_limits.sign.expires'),
		],function($api){
		//游客可以访问的接口
		$api->get('categories','CategoriesController@index')
		->name('api.categories.index');
		//需要token验证访问的接口
		$api->group(['middleware' => 'api.auth'],function($api){
			//当前登录用户信息接口
			$api->get('user','UsersController@me')
				->name('api.user.show');
			//图片资源
			$api->post('images','ImagesController@store')
				->name('api.images.store');
			//编辑登录用户信息
			$api->patch('user','UsersController@update')
				->name('api.user.update');
		});
		//短信验证码
		$api->post('vertificationCodes','VertificationCodesController@store')
		->name('api.vertificationCodes.store');
		//用户注册
		$api->post('users','UsersController@store')
		->name('api.users.store');
		//图片验证码
		$api->post('captchas','CaptchasController@store')
			->name('api.capchas.store');
		//第三方登录
		$api->post('socials/{social_type}/authorizations','AuthorizationsController@socialStore')
			->name('api.socials.authorizations.store');
		//本地登录
		$api->post('authorizations','AuthorizationsController@store')
			->name('api.authorizations.store');
						});
		//刷新token
		$api->put('authorizations/current','AuthorizationsController@update')
			->name('api.authorizations.update');
		//删除token
		$api->delete('authorizations/current','AuthorizationsController@destroy')
			->name('api.authorizations.destroy');

	});
