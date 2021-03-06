<?php

namespace App\Observers;

use App\Models\Topic;
//use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
    	$topic->body = clean($topic->body,'user_topic_body');
        $topic->excerpt = make_excerpt($topic->body);
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saved(Topic $topic){
    	// //XSS过滤
    	// $topic->body = clean($topic->body,'user_topic_body');

    	// //生成话题摘录
    	// $topic->excerpt = make_excerpt($topic->body);

    	//如果slug字段无内容，即使用翻译器对title进行翻译

    	if(!$topic->slug)
    	{
    		//$topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
            //推送队列任务
            dispatch(new TranslateSlug($topic));
    	}
    }

    public function deleted(Topic $topic){

        \DB::table('replies')->where('topic_id',$topic->id)->delete();
    }
}