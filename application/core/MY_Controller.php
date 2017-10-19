<?php
/**
 * Created by PhpStorm.
 * User: fengchu
 * Date: 17/9/22
 * Time: 下午12:34
 */
class MY_Controller extends CI_Controller {

    private  $msg = '';
    private  $status = true;
    private  $code = 1001;
    protected  $requestMode = null;
    protected  $params = [];
    protected  $paramsGet = [];
    protected  $paramsPost = [];
    protected  $cookie = [];
    private  $deBug = false;
    function __construct()
    {
        parent:: __construct();
        $this->load->helper('Common');
        $this->_init();
    }

    public function _init(){
        $this->paramsGet = $this->input->get();
        $this->paramsPost = $this->input->post();
        $this->stream = $this->input->raw_input_stream;
        //$this->load->vars(['appType' => $appType]);
        if($this->stream && $stream = json_decode($this->stream,true)){
            $this->paramsPost = array_merge($this->paramsPost,$stream);
        }
        $this->cookie = $this->input->cookie();
        $this->deBug = isset($this->paramsGet['deBug'])? $this->paramsGet['deBug'] : false;
        $this->params = array_merge($this->paramsGet,$this->paramsPost);
        if($this->requestMode && !$this->deBug){
            $this->load->service('BaseUserService');
            if($this->requestMode == REQUEST_MODE_QA){
                //$this->checkQa();
            }elseif($this->requestMode == REQUEST_MODE_OP){
                //$this->checkOp();
            }elseif($this->requestMode == REQUEST_MODE_ISNOB){
                //$this->checkISnob();
            }
        }
    }

    /*//预处理checkISnob
    public function checkISnob(){
        $sign = isset($this->params['sign']) && !empty($this->params['sign'])? $this->params['sign']:'';
        $status = $this->BaseUserService->checkISnobUserSign($sign);
        if(!$status){
            $this->setCode(10002);
            $this->setStatus(false);
            $this->outputForJson();
        }
    }

    //预处理OP
    public function checkOp(){
        if(!isset($this->cookie['SNOB_OP_TOKEN']) || empty($this->cookie['SNOB_OP_TOKEN']) ){

            $this->setCode(10002);
            $this->setStatus(false);
            $this->outputForJson();
        }
        $status = $this->BaseUserService->checkUserToken($this->cookie['SNOB_OP_TOKEN']);
        if(!$status){
            $this->setCode(10002);
            $this->setStatus(false);
            $this->outputForJson();
        }

    }*/

    public function outputForJson($data = null){
        $result = array(
            'success' => $this->status,
            'code' => $this->code,
            'msg' => $this->msg,
            'timestamp'=>getMillisecond(),
            'data'=>!empty($data)?$data: new stdClass(),

        );
        access_log(json_encode($result));
        echo json_encode($result);
        exit;
    }

    public function setStatus($status){
        return $this->status = $status;

    }

    public function setCode(){
        $this->msg = $this->getMsg($code);
        return $this->code = $code;
    }

    public function setMsg($msg){
        return $this->msg = $msg;
    }

    public function getMsg($code){
        $message = array(
            '0' => '',
            '1001' => 'sucess',
            '1002' => '参数异常',
            '1888' => '第三方授权失败',
            '4004' => 'fail',
            '4005' => '调用远程服务失败',
            '10002' => '未登录',
            '10003' => '用户第三方账号解绑失败',
            '10004' => '用户验证码不匹配',
            '10005' => '用户账号已经注册',
            '10006' => '用户不存在',
            '10007' => '验证码校验失败',
            '10008' => '验证码失效',
            '10009' => '用户邀请码使用失败',
            '11000' => '不允许发送验证码',
            '10010' => '用户昵称已注册',
            '10012' => '当前密码输入错误',
            '10013' => '用户昵称输入不合法',
            '20001' => '榜单编辑不完整',
            '20002' => '单品填写不完整',
            '20003' => '榜单不存在',
            '20004' => '删除单品失败',
            '40001' => '不能举报自己的评论',
            '50001' => '单品收藏数超过限制',
            '85000' => '不能执行该操作',
            '85001' => '不能重复执行改操作',
            '95000' => '分类重复',
            '95001' => '该分类下的单品为空',
            '650001' => '该用户已经存在，无需注册',
            '750000' => '没有回复权限',
            '750001' => '没有删除该条评论的权限',
            '750002' => '评论已被删除',
            '750003' => '评论的对象不存在',
            '20013' => '榜头标题长度太短',
            '20014' => '单品标题长度太短',
            '20015' => '榜头标题不合法',
            '20016' => '单品标题不合法',
            '850000' => '榜单不存在',
            '1000001' => '该问题不存在或已被删除',
            '3000001' => '操作问题的用户无权限',
            '2000002' => '回答未找到',
            '1000002' => '回答的问题被删除',
            '3000002' => '操作回答的用户无权限',
            '4000001' => '无剩余回答次数',
            '4000002' => '无剩余提问次数',
            '4000003' => '无剩余点赞次数',
            '4000004' => '无剩余踩次数',
            '4000005' => '无剩余分享次数',
            '4000006' => '回答无权限',
            '4000007' => '提问无权限',
            '4000008' => '无点赞权限',
            '4000009' => '无踩权限',
            '4000011' => '未满足三个回答,无法提问',
            '999999' => '版本已经过期，需要强制升级',
            '88888888' => '通用错误，显示服务器给的错误信息',
            '600001' => '日报未找到',

        );
        return isset($message[$code])?$message[$code]:'';
    }





}