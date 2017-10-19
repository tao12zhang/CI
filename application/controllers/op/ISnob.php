<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fengchu
 * Date: 17/10/10
 * Time: 下午5:48
 */
class ISnob extends MY_Controller
{
    public function __construct()
    {
        $this->requestModelMode = REQUEST_MODE_OP;
        parent::__construct();
        $this->load->Service('ContentService');
    }

    //
    public function getOnlineList()
    {
        $page = isset($this->paramsGet['page'])? $this->paramsGet['page'] : 1;
        $pageSize = isset($this->paramsGet['pageSize'])? $this->paramsGet['page'] : 10;
        $position = isset($this->paramsGet['position'])? $this->paramsGet['position'] : 0;


    }
}
