<?php

namespace app\index\controller;

use think\Controller;

class Index extends Controller
{

    public function index()
    {
        echo 'welcome';

        //recurse_copy('\\\\192.168.128.130\AppData\Users\2f7c618a-9463-45f2-9669-fc7a2e7a12d3\IssuedPreparedLessons\5dfdeafe-d669-461a-83a0-9821fb372991\Subjects\56f10031-39ba-4cc1-8079-45ec2c6d2052',ROOT_PATH . 'public/uploads/');

    }


    public function uplpadimage()
    {
        return view('index');
    }

    /**
     * 删除文件
     */
    public function delFile()
    {

        $uuid = input('post.uuid');


        $path = ROOT_PATH . 'public/uploads/subjects/' . $uuid . '/';

        deldir($path);
    }

    /**
     * 传入图片名称和课程id拼接图片http地址
     * @return [type] [description]
     */
    public function getQuizImageHttpURL()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $id = input('get.id');
        // $path = urldecode(input('get.path'));

        echo(config('QuizRelativePath') . $id . '\\');

    }

    /**
     * 获取小测验数据
     */
    public function getQuizXML()
    {

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $id = input('get.id');
        $path = urldecode(input('get.path'));

        $copyRes = copyDir($path, config('QuizAbsolutePath') . $id);
        if ($copyRes['code'] == 100) {
            $arrs = xml2arr($path, config('QuizInfo'));
            $returnJson = Array();
            $returnJson['code'] = 100;

            $returnJson['Quiz'] = $arrs['data']['Quiz'];
            $QuizContext = readFileText($path, $arrs['data']['Quiz']['QuizContextFile']);
            $QuizContext = imagepath_add_prefix($QuizContext, config('QuizRelativePath') . $id);
            $returnJson['Quiz']['QuizContex'] = $QuizContext;

            $FileName = $arrs['data']['Quiz']['StandardAnswerFile'] . "";
            $AnswerContext = readFileText($path, $FileName);
            $AnswerContext = imagepath_add_prefix($AnswerContext, config('QuizRelativePath') . $id);
            $returnJson['Quiz']['AnswerContex'] = $AnswerContext;
            //echo 111;die;
            return json($returnJson);
        } else {
            $data = Array();
            $data['code'] = 102;
            $data['error'] = $copyRes;
            $data['msg'] = 'copy c++目录失败！';
            //echo 222;die;
            return json($data);
        }

    }

    /**
     * 传入图片名称和课程id拼接图片http地址
     * @return [type] [description]
     */
    public function getImageHttpURL()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $id = input('get.id');
        // $path = urldecode(input('get.path'));

        echo(config('SubjectRelativePath') . $id . '\\');

    }

    /**
     * 获取数据
     */
    public function getXML()
    {

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $id = input('get.id');
        $path = urldecode(input('get.path'));

        $copyRes = copyDir($path, config('SubjectAbsolutePath') . $id);

        if ($copyRes['code'] == 100) {
            $arrs = xml2arr($path, config('SubjectInfo'));
            $returnJson = Array();
            $returnJson['code'] = 100;

            $returnJson['Subject'] = $arrs['data']['Subject'];
            $SubjectContext = readFileText($path, $arrs['data']['Subject']['SubjectContentFile']);
            $SubjectContext = imagepath_add_prefix($SubjectContext, config('SubjectRelativePath') . $id);
            $returnJson['Subject']['SubjectContex'] = $SubjectContext;
            //add by chen
            $returnJson['Subject']['AnswerContex'] = "";
            try {
                $FileName = $arrs['data']['Subject']['StandardAnswerFile'] . "";
                $AnswerContext = readFileText($path, $FileName);
                $AnswerContext = imagepath_add_prefix($AnswerContext, config('SubjectRelativePath') . $id);
                $returnJson['Subject']['AnswerContex'] = $AnswerContext;
            } catch (\Exception $ex) {
                \Think\Log::write('------标准答案为空 ', 'WARN');
                \Think\Log::write('------' . $ex->getMessage(), 'WARN');
            }
            //echo 111;die;
            return json($returnJson);
        } else {
            $data = Array();
            $data['code'] = 102;
            $data['error'] = $copyRes;
            $data['msg'] = 'copy c++目录失败！';
            //echo 222;die;
            return json($data);
        }

    }

    /**
     * 获取课堂活动数据
     */
    public function getActivityXML()
    {

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $id = input('get.id');
        $path = urldecode(input('get.path'));

        $copyRes = copyDir($path, config('ActivityAbsolutePath') . $id);
        if ($copyRes['code'] == 100) {
            $arrs = xml2arr($path, config('ActivityInfo'));
            $returnJson = Array();
            $returnJson['code'] = 100;

            $returnJson['Activity'] = $arrs['data']['Activity'];
            $ActivityContext = readFileText($path, $arrs['data']['Activity']['ActivityDescription']);
            $ActivityContext = imagepath_add_prefix($ActivityContext, config('ActivityRelativePath') . $id);
            $returnJson['Activity']['ActivityContex'] = $ActivityContext;

            // 附件列表的html组合
            $studentArr = $arrs['data']['Activity']['Students']['Student'];
            $studentHtml = '';
            $i = 0;
            try {
                foreach ($studentArr as $value) {
                    $i = $i + 1;
                    $studentHtml .= '<li><input type="checkbox" class="styled" id="inlineCheckbox' . $i . '" value="' . $value['StudentID'] .
                        '"><label for="inlineCheckbox' . $i . '" class="label-select">' . $value['StudentName'] . '</label></li>';
                }
            } catch (\Exception $e) {
                $studentHtml .= '<li><input type="checkbox" class="styled" id="inlineCheckbox' . $i . '" value="' . $studentArr['StudentID'] .
                    '"><label for="inlineCheckbox' . $i . '" class="label-select">' . $studentArr['StudentName'] . '</label></li>';
            }
            $returnJson['Activity']['ActivityStudents'] = $studentHtml;

            // 附件列表的html组合
            try {
                $attachmentArr = $arrs['data']['Activity']['Attachments']['Attachment'];
                $attachmentHtml = '';
                if (!is_null($attachmentArr['AttachmentName'])) {
                    $attachmentHtml .= '<li><span class="attachmentSpan" onclick="AttachmentClick(this)">' .
                        $attachmentArr['AttachmentName'] .
                        '<input type="hidden" value="' .
                        config('ActivityAbsolutePath') . $id . '\\' . $attachmentArr['AttachmentFile'] .
                        '" /></span></li>';
                } else {
                    foreach ($attachmentArr as $value) {
                        $attachmentHtml .= '<li><span class="attachmentSpan" onclick="AttachmentClick(this)">' .
                            $value['AttachmentName'] .
                            '<input type="hidden" value="' .
                            config('ActivityAbsolutePath') . $id . '\\' . $value['AttachmentFile'] .
                            '" /></span></li>';
                    }
                }
            } catch (\Exception $ex) {

            }


            $returnJson['Activity']['ActivityAttachments'] = $attachmentHtml;
            //echo 111;die;
            return json($returnJson);
        } else {
            $data = Array();
            $data['code'] = 102;
            $data['error'] = $copyRes;
            $data['msg'] = 'copy c++目录失败！';
            //echo 222;die;
            return json($data);
        }

    }

    /**
     * 回放
     */
    public function getHuiFangUrl()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $id = input('get.id');
        $path = urldecode(input('get.path'));
        $path = str_replace('\\', '/', $path);
        $fileName = substr($path, strrpos($path, '/') + 1);
        $dirTo = config('HuiFangAbsolutePath') . $id;
        if (!is_dir($dirTo)) {
            mkdir(iconv("UTF-8", "GBK", $dirTo), 0777, true);
        }
        $copyRes = copy($path, $dirTo . '\\' . $fileName);
        $url = str_replace('\\', '/', config('HuiFangRelativePath')) . $id . '/' . $fileName;
        $data = Array();
        $data["url"] = $url;
        return json($data);

    }

    /**
     * 多题
     */
    public function getDuoTiXML()
    {

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $id = input('get.id');
        $path = urldecode(input('get.path'));

        $copyRes = copyDir($path, config('DuoTiAbsolutePath') . $id);

        if ($copyRes['code'] == 100) {
            $arrs = xml2arr($path, config('SubjectInfo'));
            $returnJson = Array();
            $returnJson['code'] = 100;

            $returnJson['Subject'] = $arrs['data']['Subject'];
            $SubjectContext = readFileText($path, $arrs['data']['Subject']['SubjectContentFile']);
//            $SubjectContext = imagepath_add_prefix_duoti($SubjectContext, config('DuoTiRelativePath') . $id);
            $returnJson['Subject']['SubjectContex'] = $SubjectContext;
            $returnJson['Subject']['AnswerContex'] = "";
            try {
                $FileName = $arrs['data']['Subject']['StandardAnswerFile'] . "";
                $AnswerContext = readFileText($path, $FileName);
                $AnswerContext = imagepath_add_prefix($AnswerContext, config('DuoTiRelativePath') . $id);
                $returnJson['Subject']['AnswerContex'] = $AnswerContext;
            } catch (\Exception $ex) {
                \Think\Log::write('------标准答案为空 ', 'WARN');
                \Think\Log::write('------' . $ex->getMessage(), 'WARN');
            }
            return json($returnJson);
        } else {
            $data = Array();
            $data['code'] = 102;
            $data['error'] = $copyRes;
            $data['msg'] = 'copy c++目录失败！';
            return json($data);
        }

    }
    public function dirUrl(){
        $baseUrl = str_replace('\\','/',dirname(dirname(dirname(dirname(__FILE__)))));
        $path = "/public/uploads/temp";
        return json($baseUrl . $path);
    }
}
