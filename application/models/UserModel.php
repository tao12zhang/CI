<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fengchu
 * Date: 17/9/30
 * Time: 下午3:17
 */

class UserModel extends MY_Model
{
    public function __construct()
    {
        $this->dbtable = "User";
        parent::__construct();
        $this->load->library('session');

    }

    //注册
    public function register($newdata,$data)
    {
        if(!is_array($data)){
            $this->msg = '数据格式异常！';
            return false;
        }
        $this->session->set_userdata($newdata);
        $status = $this->db->insert($this->dbtable,$data);
        $this->status=$status;

        return $status;
    }

    //登录
    public function login($newdata,$data)
    {
        if(!is_array($data)){
            $this->msg = '数据格式异常！';
            return false;
        }
        $where =[
            'user' => $data ['user']
        ];
        $query = $this->db->from($this->dbtable)
            ->where ($where)
            ->get();
        foreach ( $query->result () as $row ) {
            $pass = $row->pass;
        }
        //print_r($pass);die;
        if ($pass == $data ['pass']) {

            $this->session->set_userdata($newdata);
            $this->status=true;
        }

        return $this->status;

    }


}

