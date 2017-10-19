<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: XFour
 * Date: 17/7/18
 * Time: 下午2:34
 */
class MY_Service{

    public  $msg = '';
    public  $status = true;

    function __construct()
    {
        log_message('info', "Service Class Initialized");
    }

    function __get($key)
    {
        $CI = & get_instance();
        return $CI->$key;
    }
}