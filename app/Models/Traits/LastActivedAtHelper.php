<?php
namespace App\Models\Traits;

use Redis;
use Carbon\Carbon;

trait LastActivedAtHelper
{
	//缓存相关，hash前缀
	protected $hash_prefix = 'larabbs_last_actived_at_';
	//字段前缀
	protected $field_prefix = 'user_';
	
	public function recordLastActivedAt()
	{

		//Redis哈希表的命名，如： larabbs_last_actived_at_2017-10-21
		$hash = $this->getHashFromDateString(Carbon::now()->toDateString());

		//字段名称，如：user_1

		$field = $this->getHashField();
		//
		//dd(Redis::hGetAll($hash));

		//当前时间，如2017-10-21 08:35:15

		$now = Carbon::now()->toDateTimeString();

		//数据写入Redis， 字段已存在会被更新

		Redis::hSet($hash,$field,$now);
	}
	//同步最后活跃时间到数据库

	public function syncUserActivedAt()
	{

		//Redis哈希表的命名，如：larabbs_last_actived_at_2017-10-21

		$hash = $this->getHashFromDateString(Carbon::yesterday()->toDateString());

		//从Redis中获取所有哈希表里的数据
		$dates = Redis::hGetAll($hash);

		//遍历，并同步到数据库中

		foreach($dates as $user_id => $actived_at){
			//会将user_1 转换为 1
			$user_id = str_replace($this->field_prefix,'',$user_id);
			
			//只有当前用户存在时才更新到数据库中
			if($user = $this->find($user_id)){
				$user->last_actived_at = $actived_at;
				$user->save();
			}
		}

		//已数据库未中心的存储，既已同步，即可删除
		Redis::del($hash);
	}

	//定义一个user的读取属性的 访问器

	public function getLastActivedAtAttribute($value)
	{

		//Redis 哈希表的命名，如：larabbs_last_at_2017-10-21
		$hash = $this->getHashFromDateString(Carbon::now()->toDateString());

		//字段名称，如：user_1
		$field = $this->getHashField();

		//三元运算符，优先选择Redis的数据，否则使用数据库中的数据
		$datetime = Redis::hGet($hash,$field) ? : $value;

		//如果存在的话，返回时间对应的Carbon实体

		if($datetime){
			return new Carbon($datetime);
		}
		else{
			//否则使用用户注册时间
			return $this->created_at;
		}
	}

	//代码复用
	public function getHashFromDateString($date)
	{
		//Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
		return $this->hash_prefix . $date;
	}

	public function getHashField()
	{
		//字段名称，如：user_1
		return $this->field_prefix . $this->id;
	}

}