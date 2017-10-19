
/**
 * Created by PhpStorm.
 * User: fengchu
 * Date: 17/9/25
 * Time: 下午7:26
 */

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {

        $this->load->view('zt/welcome_message');
    }

    //uploadFile csv
    public function uploadFile()
    {
     $file = $_FILES['file'];
     $uploadfile = dirname(__FILE__).'/'.$file["name"];
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
     @unlink($uploadfile);
     $data['goods_list'] = $goods_list;
     $this->load->view('upload',$data);
    }

    public function uploadFile2()
    {
       header("Content-Type: image/jpeg;text/html; charset=utf-8");
       $file = $_FILES['file'];
       $uploadfile = dirname(__FILE__).'/'.$file["name"];
       //保存到临时地址
       $save = move_uploaded_file($file["tmp_name"], $uploadfile);
       $fileContent = file_get_contents($uploadfile);
       //$fileContent=iconv("gd2312","utf-8",$fileContent);
       //$fileContent=mb_convert_encoding( $fileContent, 'UTF-8', 'GB2312' );
       //$fileContent = eval('return '.iconv('gbk','utf-8',var_export($fileContent)).';');
       //从临时地址中删除图片
       @unlink($uploadfile);
       //$fileContent = eval('return '.iconv('gbk','utf-8',var_export($fileContent,true)).';');
       print_r($fileContent);die;
    }

    public function uploadFile3()
    {
        $file = $_FILES['file'];
        //print_r($file);die;
        $file_path = dirname(__FILE__).'/'.$file["name"];
        //保存到临时地址
        $save = move_uploaded_file($file["tmp_name"], $file_path);

        /*if(file_exists($uploadfile)){
         $fp = fopen($uploadfile,"r");
         $str = fread($fp,filesize($uploadfile));//指定读取大小，这里把整个文件内容读取出来
         echo $str = str_replace("\r\n","<br />",$str);
        }*/

        if(file_exists($file_path)){
         $str = file_get_contents($file_path);//将整个文件内容读入到一个字符串中
         $str = str_replace("\r\n","<br />",$str);
         echo $str;
        }

        //从临时地址中删除图片
        //@unlink($uploadfile);
        die;
    }
}