<?php
namespace app\index\controller;
use think\Controller;
/**
 * 保存题目数据
 */
class Subjectsave extends Controller {
	public $Types = array("1", //填空题
	"2", //判断题
	"3", //单选题
	"4", //多选题
	"5", //问答题
	"6", //试卷
	"7", //课堂活动
		);
	/**
	 * 保存问答题
	 * @return [type] [description]
	 */
	public function savewd() {

		$uuid = input('post.uuid');
		$path = config('SubjectAbsolutePath') . $uuid . '\\';
        $htmlFilePath = 'SubjectContent.html';
		$content=modify_image_path(input('post.content'));

		$data_array = array('Title' => input('post.title'), 'ContentFile' => $htmlFilePath, 'Type' => $this -> Types[4], );

		if (!is_dir($path)) {
			mkdir($path, '0777', true);
		} else {
            delDirFile($path);
        }
        $htmlfile = fopen($path . 'SubjectContent.html', "w") or die("无法打开文件!");
        fwrite($htmlfile, $content);
        fclose($htmlfile);
		$myfile = fopen($path . 'data.json', "w") or die("无法打开文件!");
        fwrite($myfile, json_encode($data_array));
        fclose($myfile);
		$returnJson=['code'=>100,'data'=>$data_array,'path'=>ToUnixPath($path)];
		return json($returnJson);
	}

	/**
	 * 保存填空题
	 * @return [type] [description]
	 */
	public function savetk() {
		$uuid = input('post.uuid');
		$path = config('SubjectAbsolutePath') . $uuid . '\\';
		$htmlFilePath = 'SubjectContent.html';
		$content=modify_image_path(input('post.content'));

		$data_array = array('Title' => input('post.title'), 'ContentFile' => $htmlFilePath, 'Type' => $this -> Types[0], );

		if (!is_dir($path)) {
			mkdir($path, '0777', true);
		} else {
            delDirFile($path);
        }
        $htmlfile = fopen($path . 'SubjectContent.html', "w") or die("无法打开文件!");
        fwrite($htmlfile, $content);
        fclose($htmlfile);
		$myfile = fopen($path . 'data.json', "w") or die("无法打开文件!");
		fwrite($myfile, json_encode($data_array));
		fclose($myfile);
		$returnJson=['code'=>100,'data'=>$data_array,'path'=>ToUnixPath($path)];
		return json($returnJson);

	}

	/**
	 * 保存多选题
	 * @return [type] [description]
	 */
	public function saveduoxuan() {
		$uuid = input('post.uuid');
		$path = config('SubjectAbsolutePath') . $uuid . '\\';
        $htmlFilePath = 'SubjectContent.html';
		$content=modify_image_path(input('post.content'));
		$data_array = array('Title' => input('post.title'), 'ContentFile' => $htmlFilePath, 'items' => input('post.items'), 'Type' => $this -> Types[3], );

		if (!is_dir($path)) {
			mkdir($path, '0777', true);
		} else {
            delDirFile($path);
        }
        $htmlfile = fopen($path . 'SubjectContent.html', "w") or die("无法打开文件!");
        fwrite($htmlfile, $content);
        fclose($htmlfile);
		$myfile = fopen($path . 'data.json', "w") or die("无法打开文件!");
		fwrite($myfile, json_encode($data_array));
		fclose($myfile);
		$returnJson=['code'=>100,'data'=>$data_array,'path'=>ToUnixPath($path)];
		return json($returnJson);

	}

	/**
	 * 保存判断题
	 * @return [type] [description]
	 */
	public function savepd() {
		$uuid = input('post.uuid');
		$path = config('SubjectAbsolutePath') . $uuid . '\\';
        $htmlFilePath = 'SubjectContent.html';
		$content=modify_image_path(input('post.content'));
		$data_array = array('Title' => input('post.title'), 'Content' => $htmlFilePath,
//		'Rightkey' => input('post.rightkey'),
		 'Type' => $this -> Types[1], );

		if (!is_dir($path)) {
			mkdir($path, '0777', true);
		} else {
            delDirFile($path);
        }
        $htmlfile = fopen($path . 'SubjectContent.html', "w") or die("无法打开文件!");
        fwrite($htmlfile, $content);
        fclose($htmlfile);
		$myfile = fopen($path . 'data.json', "w") or die("无法打开文件!");
		fwrite($myfile, json_encode($data_array));
		fclose($myfile);
		$returnJson=['code'=>100,'data'=>$data_array,'path'=>ToUnixPath($path)];
		return json($returnJson);

	}

	/**
	 * 保存单选题
	 * @return [type] [description]
	 */
	public function savedx() {
		$uuid = input('post.uuid');
		$path = config('SubjectAbsolutePath') . $uuid . '\\';
        $htmlFilePath = 'SubjectContent.html';
		$content=modify_image_path(input('post.content'));
		$data_array = array('Title' => input('post.title'), 'ContentFile' => $htmlFilePath, 'items' => input('post.items'), 'Type' => $this -> Types[2], );

		if (!is_dir($path)) {
			mkdir($path, '0777', true);
		} else {
            delDirFile($path);
        }
        $htmlfile = fopen($path . 'SubjectContent.html', "w") or die("无法打开文件!");
        fwrite($htmlfile, $content);
        fclose($htmlfile);
		$myfile = fopen($path . 'data.json', "w") or die("无法打开文件!");
		fwrite($myfile, json_encode($data_array));
		fclose($myfile);
		$returnJson=['code'=>100,'data'=>$data_array,'path'=>ToUnixPath($path)];
		return json($returnJson);

	}
	
	/**
	 * 保存试卷信息
	 * @return [type] [description]
	 */
	public function savesj() {
		$qid = uniqid();
		$path = config('QuizAbsolutePath') . $qid . '\\';
		$data_array = array('Title' => input('post.title'), 'Blankpaper' => '', 'Rightkey' => '', 'Type' => $this -> Types[5], );
		// 获取表单上传文件
		$files = request() -> file('image');
		$Blankpaper = $files[0] -> rule('uniqid') -> move($path . '/images/');
		$Rightkey = $files[1] -> rule('uniqid') -> move($path . '/images/');
		$data_array['Blankpaper'] = '\\images\\' . $Blankpaper -> getFilename();
		$data_array['Rightkey'] = '\\images\\' . $Rightkey -> getFilename();
		if ($Blankpaper && $Rightkey) {
			$myfile = fopen($path . '\\' . 'data.json', "w") or die("无法打开文件!");
			fwrite($myfile, json_encode($data_array));
			fclose($myfile);
			$returnJson=['code'=>100,'data'=>$data_array,'path'=>ToUnixPath($path)];
			return json($returnJson);

		} else {
			// 上传失败获取错误信息
//			$data_array = array(code => 500, err1 => $Blankpaper -> getError(), err2 => $Rightkey -> getError());
//		return json(data_array);
			$returnJson=['code'=>500,err1 => $Blankpaper -> getError(), err2 => $Rightkey -> getError()];
		return json($returnJson);
		}

	}

    /**
     * 截屏保存
     * @return [type] [description]
     */
    public function savePrtScr() {
        $uuid = input('post.uuid');
        $items = input('post.items');
        $path = config('SubjectAbsolutePath') . $uuid . '\\';
        $toImagePath = $path . 'Images\\';
        $fromImageFull = input('post.imagepath');
        $fromImageFile = basename($fromImageFull);
        \Think\Log::write('=====$uuid:'.$uuid,'WARN');
        \Think\Log::write('=====$path:'.$path,'WARN');
        \Think\Log::write('=====$fromImageFull:'.$fromImageFull,'WARN');
        \Think\Log::write('=====$toImagePath:'.$toImagePath,'WARN');
        \Think\Log::write('=====$fromImageFile:'.$fromImageFile,'WARN');
        

        $htmlFilePath = 'SubjectContent.html';


        $data_array = array('Title' => input('post.title'), 'ContentFile' => $htmlFilePath,'items' => input('post.items'),  'Type' => input('post.type'));

        if (!is_dir($toImagePath)) {
            mkdir($toImagePath, '0777', true);
        }

        //$returnJson=['code'=>100,'data'=>$fromImageFull,'path'=>ToUnixPath($path)];
        //return json($returnJson);
        try {
            copy($fromImageFull, $toImagePath . $fromImageFile);
        } catch (\Exception $e) {
        	\Think\Log::write('=====savePrtScr:'.$e->getMessage(),'WARN');
            $returnJson=['code'=>102,'data'=>$e->getMessage()];
            \Think\Log::write('=====$returnJson:'.$returnJson,'WARN');
            return json($returnJson);
        }


        $content='<p><img src = "images/' . $fromImageFile . '" style = ""/></p>';

        $htmlfile = fopen($path . 'SubjectContent.html', "w") or die("无法打开文件!");
        fwrite($htmlfile, $content);
        fclose($htmlfile);
        $myfile = fopen($path . 'data.json', "w") or die("无法打开文件!");
        fwrite($myfile, json_encode($data_array));
        fclose($myfile);
        $returnJson=['code'=>100,'data'=>$data_array,'path'=>ToUnixPath($path)];
        return json($returnJson);
    }

}
