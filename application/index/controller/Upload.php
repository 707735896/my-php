<?php
namespace app\index\controller;
/**
 * 图片上传
 */
class Upload {
	
	public function test() {
		return view();
	}
	/**
	 * 上传图片的接口
	 */
	public function image() {
		
		$path = ROOT_PATH . 'public/uploads/draw/';
		// 获取表单上传文件
		$files = request() -> file('image');
		$info = $files[0] -> rule('uniqid') -> move($path);
	
		if ($info) {
			$url=config('URL').'public/uploads/draw/'.$info->getFilename();
			$returnJson=['code'=>100,'url'=>$url];
			return json($returnJson);	
		} else {

			$returnJson=['code'=>500,err => $info -> getError()];
			return json($returnJson);	
		}
		
	}

}
