<?php
namespace app\index\controller;
use think\Controller;

/**
 * 主观题
 */
class Subjective  extends Controller {

	/**
	 * 填空题
	 * @return [type] [description]
	 */
	public function tk() {

		return view('tk');
	}

	/**
	 * 问答题
	 * @return [type] [description]
	 */
	public function wd() {
		return view('wd');

	}

	/**
	 * 新增问答
	 * @return [type] [description]
	 */
	public function addwd() {
		$this -> assign('uuid', uniqid());

		return view('addquestion');

	}

	/**
	 * 修改问答
	 * @return [type] [description]
	 */
	public function updatewd() {

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
			//替换富文本里面的图片路径
			$SubjectContext = imagepath_add_prefix($SubjectContext, config('SubjectRelativePath') . $id);

			$this -> assign('SubjectContexFile', $SubjectContext);
			$this -> assign('data', $data['data']['Subject']);

			$this -> assign('uuid', $id);

			return view('addquestion');
		} else {
			$this -> error('传入的参数不完整！');
		}
	}

	/**
	 * 新增填空
	 * @return [type] [description]
	 */
	public function addtk() {
		$this -> assign('uuid', uniqid());

		return view('addsummary');
	}

	/**
	 * 修改填空
	 * @return [type] [description]
	 */
	public function updatetk() {
		//移动c++目录到我的目录下面
		$id = input('get.id');
		$path = input('get.path');
		$copyRes = copyDir($path, config('SubjectAbsolutePath') . $id);

		if (input('?get.path') && $path != '' && input('?get.id')) {
			//读取xml数据
			$xmlArr = xml2arr($path, config('SubjectInfo'));
			if ($xmlArr['code'] == 101) {
				$this -> error('暂未读取到有题目内容！');
			}
			

			//读取题干富文本
			$SubjectContext = readFileText($path, $data['data']['Subject']['SubjectContentFile']);
			//替换富文本里面的图片路径
			$SubjectContext = imagepath_add_prefix($SubjectContext, config('SubjectRelativePath') . $id);

			$this -> assign('SubjectContexFile', $SubjectContext);
			$this -> assign('data', $xmlArr['data']['Subject']);
			$this -> assign('uuid', $id);

			return view('addsummary');
		} else {
			$this -> error('传入的参数不完整！');
		}
	}

	/**
	 * 答案列表
	 */
	public function answerlist() {

		return view('answerList');
	}

    /**
     * 答案文件拷贝
     */
    public function answercopy() {
        $qid = uniqid();
        $path = config('TempAbsolutePath') . $qid . '\\';
        $fromImageFull = input('post.answerFile');
        $fileName = basename($fromImageFull);
        if (!is_dir($path)) {
            mkdir($path, '0777', true);
        }

        try {
            copy($fromImageFull, $path . $fileName);
        } catch (\Exception $e) {
            $returnJson=['code'=>102,'data'=>$e->getMessage()];
            return json($returnJson);
        }

        $returnJson=['code'=>100,'path'=>config('TempRelativePath') . $qid . '\\'.$fileName];
        return json($returnJson);
    }

	public function test() {

		return view('test');
	}

	/**
	 * 标准答案
	 */
	public function standardanswers() {
		return view('standardanswers');
	}

	/**
	 * 白板
	 */
	public function whiteBoard() {
		return view('whiteBoard');
	}

}
