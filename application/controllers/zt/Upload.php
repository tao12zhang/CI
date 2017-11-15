<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fengchu
 * Date: 17/9/25
 * Time: 下午7:26
 */

class Upload extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->Service('UploadService');

    }

    public function index()
    {
        echo
        "<form action=\"./upload\" method=\"post\" enctype=\"multipart/form-data\">
            <label for=\"file\">文件名：</label>
            <input type=\"file\" name=\"file\" id=\"file\"><br>
            <input type=\"submit\" name=\"submit\" value=\"提交\">
        </form> ";
    }

    //upload
    public function upload()
    {
        //$type = isset($this->paramsGet['type'])? $this->paramsGet['type'] : 0;
        $file = $_FILES['file'];
        $result = $this->UploadService->uploadCsv($file);
    }



}