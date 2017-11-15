<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fengchu
 * Date: 17/11/6
 * Time: 下午3:30
 */

class UploadService extends MY_Service{

    public function __construct(){
        parent::__construct();

    }

    //上传图片
    /***
     *文件上传
     * @$data    上传内容或文件路径
     * @$fileName   指定上传文件名
     * @$type    1:字符内容上传  2:文件上传
     ***/
    public function uploadFile($data,$fileName='',$type=1){
        $result = false;
        if(empty($data)){
            $this->msg = '上传数据空！';
            return  $result;
        }

        $data = $type==2?file_get_contents($data):$data;

        $fileName = $fileName?$fileName:date("YmdHis_").rand(10000,99999).'.dat';

        $method = 'POST';
        //$sign = $this->uploadSign($fileName,$method);
        $header = ["Authorization:".$sign['authorization'],"Date:".$this->dateTime];
        $url = $this->host.':'.$this->port.$sign['uri'].'?json';
        $uploadResult = curlHttpClient($url, $data,$method,[],$header);
        if($uploadResult && $uploadResult = json_decode($uploadResult,true)){
            if($uploadResult['status']['msg'] == 'ok'){
                return $uploadResult['result'];
            }else{
                $this->msg = $uploadResult['status']['msg']?$uploadResult['status']['msg']:'上传失败！';
                return  $result;
            }
        }
        $this->msg = $uploadResult;
        return  $result;
    }

    //上传csv
    public function uploadCsv($file){
        //print_r(APPPATH.'file/'.$file["name"]);die;
        $uploadfile = APPPATH.'file/'.$file["name"];
        //保存到临时地址
        $save = move_uploaded_file($file["tmp_name"], $uploadfile);
        $fileContent = fopen($uploadfile,'r');
        while ($data = fgetcsv($fileContent)) {
            $data = eval('return '.iconv('gbk','utf-8',var_export($data,true)).';');
            $goods_list[] = $data;
        }
        //print_r($goods_list);
        fclose($fileContent);
        //从临时地址中删除图片
        //@unlink($uploadfile);
        $data['goods_list'] = $goods_list;
        print_r($data);
        //$this->load->view('upload',$data);
    }
    //上传视频
    public function uploadVideo(){

    }




}