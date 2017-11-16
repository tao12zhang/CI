<?php
/**
 * Created by PhpStorm.
 * User: fengchu
 * Date: 17/9/30
 * Time: 下午2:00
 */

class Login extends MY_Controller {

    private $pass = '';

    public function __construct() {
        parent::__construct ();
        $this->load->helper ( array (
            'form',
            'url'
        ) );
        //$this->load->Model('UserModel');
        $this->load->library ('form_validation');


    }

    public function index() {
        $this->load->view ( 'login' );
    }

    //注册与登录
    public function formsubmit() {


        $this->form_validation->set_rules ( 'username', 'Username', 'required' );
        $this->form_validation->set_rules ( 'password', 'Password', 'required' );

        if ($this->form_validation->run () == FALSE) {
            $this->load->view ( 'login' );
        } else {
            if (isset ( $_POST ['submit'] ) && ! empty ( $_POST ['submit'] )) {
                $data = array (
                    'user' => $_POST ['username'],
                    'pass' => md5($_POST ['password'])
                );
                $newdata = array(
                    'username'  =>  $data ['user'] ,
                    'userip'     => $_SERVER['REMOTE_ADDR'],
                    'luptime'   =>time()
                );

                if ($_POST ['submit'] == 'login') {
                    $status = $this->UserModel->login($newdata,$data);
                    return $status;

                } else if ($_POST ['submit'] == 'register') {
                    $status = $this->UserModel->register($newdata,$data);
                    return $status;

                } else {
                    $this->session->sess_destroy();
                    $this->load->view ( 'login' );
                }
            }
        }
    }





}