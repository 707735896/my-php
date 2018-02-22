<?php
namespace app\index\controller;
/**
 * 投票
 */
class Vote {
	public function index() {


		xml2arr('D:\LiuHuanHui\Git\AroundU_WEB\application\test.xml');
		return view('index');
	}
	
	/**
	 * 投票器
	 */
	public function tpq() {
		return view('tpq');
	}

}

