<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: XFour
 * Date: 17/7/27
 * Time: 上午10:26
 */
class WechatModel extends MY_Model
{


    public function __construct()
    {
        $this->dbtable = 'PublicUser';
        parent::__construct();
        $this->load->library('Redis_db');
        $this->redis_db->connect();
    }

    public function setR($key, $val, $time){
        $this->redis_db->getRedis()->set($key,$val,$time);
    }
    
    public function keys(){
        return $this->redis_db->getRedis()->keys("activite:qixi:*");
    }


}