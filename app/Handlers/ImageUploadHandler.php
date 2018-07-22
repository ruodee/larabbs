<?php

namespace App\Handlers;
use Image;

class ImageUploadHandler
{
	//允许上传的图片文件后缀名
	protected $allowed_ext = ["png","jpg","gif","jpeg"];

	public function save($file,$folder,$file_prefix,$max_width=false){
		//构建存储的文件夹规则，值如：uploads/images/avatar/201709/21/
		//文件夹切割能让查找效率更高。
		$folder_name = "uploads/images/$folder/".date("Ym/d",time());

		//文件具体存储的物理路径，'public_path()'获取的是'public'文件夹的物理路径
		//值如：/home/vagrant/code/larabbs/public/upload/image/avatars/201807/22
		$upload_path = public_path().'/'.$folder_name;

		//获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
		$extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

		//拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
		//值如： 1——1493521050——7BVc9V9UJp.png
		$filename = $file_prefix.'_'.time().'_'.str_random(10).'.'.$extension;

		//如果上传的不是图片将终止操作
		if(! in_array($extension,$this->allowed_ext))
		{
			return false;
		}

		//将图片移动到我们的目标存储路径中
		$file->move($upload_path,$filename);

		//如果限制了图片的最大宽度，则进行裁剪图片
		if($max_width && $extension != 'gif'){ 
			//此类中封装的方法，用于裁剪图片
		$this->reduceSize($upload_path.'/'.$filename,$max_width);
		}
		return [
				'path' => config('app.url')."/$folder_name/$filename",
		];
	}
	//创建图片剪裁方法reduceSize()
	public function reduceSize($file_path,$max_width){
		//先实例化，传参是文件的磁盘物理路径
		$image = Image::make($file_path);
		//进行大小调整的操作
		$image->resize($max_width,null,function($constraint){
			//设定宽度是$max_width，高度等比例缩放
			$constraint->aspectRatio();
			//防止裁图时图片尺寸变大
			$constraint->upsize();
		});

		//对图片修改后进行保存
		$image->save();

	}
}