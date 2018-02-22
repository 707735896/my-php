<?php
namespace app\index\controller;
use think\Controller;

/**
 * 小测验
 */
class Exam  extends Controller{
	/**
	 * 首页
     *
     *
	 * @return [type] [description]
	 */
	public function index() {
		return view('xcy');
	}

	/**
	 * 新增
	 * @return [type] [description]
	 */
	public function add() {
		$this -> assign('uuid', uniqid());

		return view('addexam');
	}

	/**
	 * 修改
	 * @return [type] [description]
	 */
	public function update() {
		$this -> assign('uuid', input('get.id'));

		return view('addexam');

        //移动c++目录到我的目录下面
        $id = input('get.id');
        $path = input('get.path');
        $copyRes = copyDir($path, config('QuizAbsolutePath') . $id);

        if (input('?get.path') && $path != '' && input('?get.id')) {
            //读取xml数据
            $xmlArr = xml2arr($path, config('QuizInfo'));
            if ($xmlArr['code'] == 101) {
                $this -> error('暂未读取到有题目内容！');
            }

            $QuizContext = readFileText($path, config('QuizContexFile'));
            $QuizContext = imagepath_add_prefix($QuizContext,config('QuizRelativePath').$id);

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
	 * 标准答案
	 */
	public function standardanswers() {
		return view('standardanswers');
	}

}
