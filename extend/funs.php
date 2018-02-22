<?php
require('phpQuery/phpQuery.php');

/**
 *获取html文本里的img
 * @param string $content
 * @return array
 */
function sp_getcontent_imgs($content){
	$doc=phpQuery::newDocumentHTML($content);
	
	$imgs=pq('*')->find("img");
	$imgs_data=array();
	if($imgs->length()){
		foreach ($imgs as $img){
			$img=pq($img);
			$im=$img->attr("src");
			$imgs_data[]=$im;
		}
	}
	phpQuery::$documents=null;
	return $imgs_data;
}
/**
 * 把c++的html文件字符串里的image 路径加上path
 */
function imagepath_add_prefix($content,$prefix){
	return str_replace('images/', $prefix.'\\images\\', $content);
//	    return preg_replace('/src=/',$prefix,$content);  

}

/**
 * 把我本地的富文本html字符串替换成当前目录下的
 */
function modify_image_path($content)  
{  
//  preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU",$content,$matches);  
//  if(!empty($matches)){  
//      $imgurl = $matches[2];  
//      foreach($imgurl as $val){  
//          preg_match("/^.*\//",$val,$res);   
//				                //先匹配出来图片存储的路径  
//          $content = str_replace($res[0],$url,$content);          //将路径全部改成"images/"  
//	    
//      }  
//  }else{  
//      return FALSE;  
//  }  
    return preg_replace('/\/public\/uploads\/subjects\/(\w)+\//','',$content);  
}  
/**
 * 读取xml
 */
function xml2arr($dir, $file = '') {
	$path = $dir;
	if (!empty($file)) {
		$path .= '\\' . $file;
	}
//	$path = str_replace('\\\\','\\',$path);
	//echo $path;
	if (file_exists($path)) {
		$str = file_get_contents($path);
		$obj = simplexml_load_string($str);
		$json = json_encode($obj);
		$arr = json_decode($json, true);
		$data=[];
		$data['code']=100;
		$data['data']=$arr;
		return $data;
	} else {
		$data=[];
		$data['code']=101;
		$data['msg']='读取的xml目录不存在';
		trace('您要读取的xml目录不存在' . $path, 'error');
			return $data;
	}
}

/**
 * 读取文件
 */
function readFileText($dir, $file = '') {

	$file_path = $dir;
	if (!empty($file)) {
		$file_path .= '\\' . $file;
	}
	if (file_exists($file_path)) {
		$str = file_get_contents($file_path);
//		$data=[];
//		$data['code']=100;
//		$data['data']=$str;
		return $str;
	} else {
//		$data=[];
//		$data['code']=101;
//		$data['msg']='读取的xml目录的富文本不存在';
		trace('读取的xml目录的富文本不存在'.$file_path, 'error');
		return "";
	}

}
/**
 * COPY 目录和文件
 */
function recurse_copy($src,$dst){
 $dir=opendir($src);
	 @mkdir($dst);
	 while(false!==($file=readdir($dir))){
	  if(($file!='.' )&&($file!='..')){
	            if(is_dir($src.'/'.$file)){
	                recurse_copy($src.'/'.$file,$dst.'/'.$file);
	            }
	            else{
	    	copy($src.'/'.$file,$dst.'/'.$file);
	   }
	  }
 	}
 closedir($dir);
}

/**
 * 复制c++目录到php目录
 */
function copyDir($dirSrc,$dirTo)
{
	$res=Array();
    if(is_file($dirTo))
    {
    	$res['code']=101;
		$res['msg']='这不是一个目录';
		return $res;
    }
//echo $dirSrc;
//		if (!is_dir($dirSrc)){
//		$res['code']=101;
//		$res['msg']='要copy的c++目录不存在';
//		
//		return $res;
//	}  
	
	if (!is_dir($dirTo)){  
		mkdir(iconv("UTF-8", "GBK", $dirTo),0777,true); 
	}
	
    if(!file_exists($dirTo))
    {
        mkdir($dirTo);
    }
    if($handle=opendir($dirSrc))
    {
        while($filename=readdir($handle))
        {
            if($filename!='.' && $filename!='..')
            {
                $subsrcfile=$dirSrc . '/' . $filename;
                $subtofile=$dirTo . '/' . $filename;
                if(is_dir($subsrcfile))
                {
                    copyDir($subsrcfile,$subtofile);//再次递归调用copydir
                   
                }
                if(is_file($subsrcfile))
                {
                   copy($subsrcfile,$subtofile);
                }
            }
        }
        closedir($handle);
		$res['code']=100;
		return $res;
    }

}
/**
 * 转成unix路径格式
 */
function ToUnixPath($path) {
	$path = realpath($path);
	$path = str_replace('\\', '/', $path);
	
	return realpath(str_replace('D:/LiuHuanHui/Git/AroundU_WEB/','\\\192.168.128.63\\AroundU_WEB\\',$path));
}
/**
 * 转成unix路径格式
 */
function ToWindowPath($path) {
	$path = realpath($path);
	$path = str_replace('\\', '/', $path);
	
	return realpath(str_replace('D:/LiuHuanHui/Git/AroundU_WEB/','\\\192.168.128.63\\AroundU_WEB\\',$path));
}

/**
 * 将内容进行UNICODE编码
 */
function unicode_encode($name)
{
    $name = iconv('UTF-8', 'UCS-2', $name);
    $len = strlen($name);
    $str = '';
    for ($i = 0; $i < $len - 1; $i = $i + 2)
    {
        $c = $name[$i];
        $c2 = $name[$i + 1];
        if (ord($c) > 0)
        {
            // 两个字节的文字
            $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
        }
        else
        {
            $str .= $c2;
        }
    }
    return $str;
}

/**
 * 删除目录下的文件
 */
function delDirFile($dir) {
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
            if(!is_dir($fullpath)) {
                unlink($fullpath);
            }
        }
    }

    closedir($dh);

    return true;
}