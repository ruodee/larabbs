<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedCategoriesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //初始化categories的数据
        $categories = [
            [
                'name' => '分享',
                'description' => '分享创造，分享发现，分享精神',
            ],
            [
                'name' => '教程',
                'description' => '开发技巧，学习教程，推荐扩展包。'
            ],
            [
                'name' => '问答',
                'description' => '注意提问的技巧，请保持虚心友善，互相帮助。'
            ],
            [
                'name' => '公告',
                'description' => '通知公告，热点推荐',
            ],
        ];
        DB::table('categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //清空categories表里面的数据
        DB::table('categories')->truncate();
    }
}
