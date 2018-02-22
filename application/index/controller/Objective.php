<?php
namespace app\index\controller;
//namespace application\index\controller;
use think\Controller;
/**
 * 客观题
 */
class Objective extends Controller {

	/**
	 * 查看单选题
	 * @return [type] [description]
	 */
	public function dxt() {
		//		$this -> assign('uuid', uniqid());
		return view('dxt');
	}

	/**
	 * 添加单选题
	 * @return [type] [description]
	 */
	public function adddxt() {
		$this -> assign('uuid', uniqid());
		return view('adddxt');
	}

	/**
	 * 修改单选题
	 * @return [type] [description]
	 */
	public function updatedxt() {
		//copy c++目录
		$id = input('get.id');
		$path = input('get.path');
		$copyRes = copyDir($path, config('SubjectAbsolutePath') . $id);

		if (input('?get.path') && $path != '' && input('?get.id')) {
			$data = xml2arr($path, config('SubjectInfo'));
			if (empty($data)) {
				$this -> error('暂未读取到有题目内容！');
			}

			//读取题干
			$SubjectContext = readFileText($path, $data['data']['Subject']['SubjectContentFile']);
			//替换题干富文本里面的图片路径
			$SubjectContext = imagepath_add_prefix($SubjectContext, config('SubjectRelativePath') . $id);

			$this -> assign('data', $data['data']['Subject']);
			$this -> assign('SubjectContexFile', $SubjectContext);
			$this -> assign('uuid', input('get.id'));

			return view('adddxt');
		} else {
			$this -> error('传入的参数不完整！');
		}

	}

	/**
	 * 查看多选题
	 * @return [type] [description]
	 */
	public function duoxt() {
		//		$this -> assign('uuid', uniqid());
		return view('duoxt');
	}

	/**
	 * 新增多选题
	 * @return [type] [description]
	 */
	public function addduoxt() {
		$this -> assign('uuid', uniqid());
		return view('addduoxt');
	}

	/**
	 * 修改多选题
	 * @return [type] [description]
	 */
	public function updateduoxt() {
		$id = input('get.id');
		$path = input('get.path');
		$copyRes = copyDir($path, config('SubjectAbsolutePath') . $id);
		if (input('?get.path') && $path != '' && input('?get.id')) {
			$data = xml2arr($path, config('SubjectInfo'));
			if (empty($data)) {
				$this -> error('暂未读取到有题目内容！');
			}

			//读取题干
			$SubjectContext = readFileText($path, $data['data']['Subject']['SubjectContentFile']);
			//替换富文本里面的图片路径
			$SubjectContext = imagepath_add_prefix($SubjectContext, config('SubjectRelativePath') . $id);

			$this -> assign('data', $data['data']['Subject']);
			$this -> assign('SubjectContexFile', $SubjectContext);

			$this -> assign('uuid', input('get.id'));

			return view('addduoxt');
		} else {
			$this -> error('传入的参数不完整！');
		}
	}

	/**
	 * 查看判断题
	 * @return [type] [description]
	 */
	public function pdt() {

		return view('pdt');
	}

	/**
	 * 判断题
	 * @return [type] [description]
	 *
	 */
	public function addpdt() {
		$this -> assign('uuid', uniqid());

		return view('addpdt');
	}

	/**
	 * 修改判断题
	 * @return [type] [description]
	 */
	public function updatepdt() {
		$id = input('get.id');
		$path = input('get.path');
		$copyRes = copyDir($path, config('SubjectAbsolutePath') . $id);
		if (input('?get.path') && $path != '' && input('?get.id')) {
			$data = xml2arr($path, config('SubjectInfo'));
			if (empty($data)) {
				$this -> error('暂未读取到有题目内容！');
			}

			//读取题干
			$SubjectContext = readFileText($path, $data['data']['Subject']['SubjectContentFile']);
			//替换富文本里面的图片路径
			$SubjectContext = imagepath_add_prefix($SubjectContext, config('SubjectRelativePath') . $id);

			$this -> assign('data', $data['data']['Subject']);
			$this -> assign('SubjectContexFile', $SubjectContext);
			$this -> assign('uuid', input('get.id'));

			return view('addpdt');
		} else {
			$this -> error('传入的参数不完整！');
		}
	}

	/**
	 * 多题展示页
	 * @return [type] [description]
	 */
	public function duot() {

		return view('duot');
	}

	/**
	 * 综合统计页
	 * @return [type] [description]
	 */
	public function ptotal() {

		return view('ptotal');
	}

	/**
	 * 小题统计页
	 * @return [type] [description]
	 */
	public function ctotal() {

		return view('xtotal');
	}
	
}
