<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: XFour
 * Date: 17/7/31
 * Time: 下午4:34
 */
class BaseUserService extends MY_Service
{

    private $userId = null;
    public function __construct()
    {
        parent::__construct();
    }

    /*public function qaNeedLogin(){
        $methods = [
            'doClock','getMissionListOfUser'
        ];
        $func = $this->router->fetch_method();
        return in_array($func,$methods);
    }

    public function ISnobNeedLogin(){
        $methods = [
            ''
        ];
        $func = $this->router->fetch_method();
        return in_array($func,$methods);
    }


    //验证用户信息
    public function checkUserToken($token){
        $tokenData = $this->OpUserModel->getUserByToken($token);
        //是否登录
        if(!isset($tokenData['status']) || $tokenData['status'] != 0) {
            return false;
        }
        if(!isset($tokenData['username'])){
            return false;
        }
        $userInfo = $this->OpUserModel->getUserByName($tokenData['username']);
        if(!isset($userInfo['id']) || !isset($userInfo['username'])){
            return false;
        }
        $userData = [
            'userType'=>'admin',
            'userId' => $userInfo['id'],
            'userName' => $userInfo['username']
        ];
        $this->load->vars(['currentUser'=>$userData]);
        return true;

    }

    //验证用户信息checkUserSign
    public function checkUserSign($sign = ''){

        $userId = $this->UserModel->getUserIdBySign($sign);
        //是否登录
        if(!$userId && $this->qaNeedLogin()) {
            return false;
        }
        $userData = [
            'userType'=>'app',
            'userId' => (int)$userId,
        ];
        $this->load->vars(['currentUser'=>$userData]);
        return true;

    }
    //验证ISnob用户信息checkUserSign
    public function checkISnobUserSign($sign = ''){

        $userId = $this->ISnobUserModel->getUserIdBySign($sign);
        //是否登录
        if(!$userId && $this->ISnobNeedLogin()) {
            return false;
        }
        $userData = [
            'userType'=>'ISnob',
            'userId' => (int)$userId,
        ];
        $this->load->vars(['currentUser'=>$userData]);
        return true;

    }*/

}