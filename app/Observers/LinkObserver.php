<?php

namespace App\Observers;

use App\Models\Link;
use Cache;

class LinkObserver
{
	//在保存时清空cache_key 对应的缓存
	public function saved(Link $link)
	{
		Cache::forget($link->cache_key);
	}
}