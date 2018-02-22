<?php
namespace app\index\controller;
use think\Controller;



/**
 * 课堂活动
 */
class Activities extends Controller{
    /**
     * 首页
     *
     *
     * @return [type] [description]
     */
    public function index() {
        return view('activity');
    }
	/**
	 * 添加课堂活动
	 * @return [type] [description]
	 */
	public function add() {
		$this -> assign('uuid', uniqid());

		return view('index');
	}
	/**
	 * 修改课堂活动
	 * @return [type] [description]
	 */
	public function update() {

		return view('index');
	}

}
