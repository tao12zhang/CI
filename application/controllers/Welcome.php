<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct(){

        $this->requestMode = REQUEST_MODE_OP;
        parent::__construct();
        $this->load->Service('BaseUserService');

    }

	public function index()
	{

		//$this->load->view('welcome_message');
        $dataResult =  [
            'haha' =>[1,2,3,4,5,6,7,8,9,10],
            'hehe' =>[1,2,3,4,5,6,7,8,9,10],
            'heihei' =>[1,2,3,4,5,6,7,8,9,10]
        ];  //todo:导出数据（自行设置）
        $headTitle = "XX保险公司 优惠券赠送记录";
        $time = date('YmdHis');
        $title = "优惠券记录".$time;
        $headtitle= "<tr style='height:0px;border-style:none;><th border=\"0\" style='height:30px;width:270px;font-size:22px;' colspan='10' >{$headTitle}</th></tr>";
        $titlename = "<tr> 
               <th style='width:70px;' >合作商户</th> 
               <th style='width:70px;' >会员卡号</th> 
               <th style='width:70px;'>车主姓名</th> 
               <th style='width:150px;'>手机号</th> 
               <th style='width:70px;'>车牌号</th> 
               <th style='width:100px;'>优惠券类型</th> 
               <th style='width:70px;'>优惠券名称</th> 
               <th style='width:70px;'>优惠券面值</th> 
               <th style='width:70px;'>优惠券数量</th> 
               <th style='width:70px;'>赠送时间</th> 
           </tr>";
        $filename = $title.".xls";

        $this->excelData($dataResult,$titlename,$headtitle,$filename);
	}

	public function downExcel()
    {
        $indexKey = array('id','username','sex','age','haha');
        $list = array(
            array('id'=>1,'username'=>'YQJ','sex'=>'男','age'=>24,'haha'=>556),
            array('id'=>1,'username'=>'YQJ','sex'=>'男','age'=>24,'haha'=>55),
            array('id'=>1,'username'=>'YQJ','sex'=>'男','age'=>24,'haha'=>55),
            array('id'=>1,'username'=>'YQJ','sex'=>'男','age'=>24,'haha'=>55),
            array('id'=>1,'username'=>'YQJ','sex'=>'男','age'=>24,'haha'=>55),

        );
        $filename = 'nihao'.date('YmdHis');
        $startRow = 3;
        $template = APPPATH.'excel/template.xls';
        $this->exportExcel($list,$filename,$template,$indexKey,$startRow,$excel2007=false);

    }

    public function downTable()
    {
        $filename = '提现记录'.date('YmdHis');
        $header = array('编号','姓名','性别','年龄');
        $index = array('id','username','sex','age');
        $list = array(array('id'=>1,'username'=>'YQJ','sex'=>'男','age'=>24));
        $this->createtable($list,$filename,$header,$index);
    }

    public function excelData($datas,$titlename,$title,$filename){
        //$str = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"\r\nxmlns:x=\"urn:schemas-microsoft-com:office:excel\"\r\nxmlns=\"http://www.w3.org/TR/REC-html40\">\r\n<head>\r\n<meta http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n</head>\r\n<body>";
        $str .="<table border=1>";
        $str .= $title;
        $str .= "<head>".$titlename."</head>";
        foreach ($datas  as $key=> $rt )
        {
            $str .= "<tr>";
            foreach ( $rt as $k => $v )
            {
                $str .= "<td>{$v}</td>";
            }
            $str .= "</tr>\n";
        }
        $str .= "</table></body></html>";
        //echo $str;die;
        header( "Content-Type: application/vnd.ms-excel; name='excel'" );
        header( "Content-type: application/octet-stream" );
        header( "Content-Disposition: attachment; filename=".$filename );
        header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );
        exit( $str );
    }
    /**
     * 创建(导出)Excel数据表格
     * @param  array   $list        要导出的数组格式的数据
     * @param  string  $filename    导出的Excel表格数据表的文件名
     * @param  array   $indexKey    $list数组中与Excel表格表头$header中每个项目对应的字段的名字(key值)
     * @param  array   $startRow    第一条数据在Excel表格中起始行
     * @param  [bool]  $excel2007   是否生成Excel2007(.xlsx)以上兼容的数据表
     * 比如: $indexKey与$list数组对应关系如下:
     *     $indexKey = array('id','username','sex','age');
     *     $list = array(array('id'=>1,'username'=>'YQJ','sex'=>'男','age'=>24));
     */
    function exportExcel($list,$filename,$template,$indexKey,$startRow=1,$excel2007=false){
        //文件引入
        //print(APPPATH.'libraries/excel/PHPExcel.php');die;
        require_once APPPATH.'libraries/excel/PHPExcel.php';
        require_once APPPATH.'libraries/excel/PHPExcel/Writer/Excel2007.php';

        if(empty($filename)) $filename = time();
        if( !is_array($indexKey)) return false;

        $header_arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M', 'N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        //初始化PHPExcel()
        $objPHPExcel = new PHPExcel();

        //设置保存版本格式
        if($excel2007){
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $filename = $filename.'.xlsx';
        }else{
            $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
            $filename = $filename.'.xls';
        }

        //$template = APPPATH.'excel/template.xls';          //使用模板
        $objPHPExcel = PHPExcel_IOFactory::load($template);     //加载excel文件,设置模板

        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);  //设置保存版本格式

        //接下来就是写数据到表格里面去
        $objActSheet = $objPHPExcel->getActiveSheet();
        //$objActSheet->setCellValue('A2',  "活动名称：江南极客");
        //$objActSheet->setCellValue('C2',  "导出时间：".date('Y-m-d H:i:s'));
        //$startRow = 1;
        foreach ($list as $row) {
            foreach ($indexKey as $key => $value){
                //这里是设置单元格的内容
                $objActSheet->setCellValue($header_arr[$key].$startRow,$row[$value]);
            }
            $startRow++;
        }

        // 下载这个表格，在浏览器输出
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename='.$filename.'');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }

    protected function createtable($list,$filename,$header=array(),$index = array()){
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=".$filename.".xls");
        $teble_header = implode("\t",$header);
        $strexport = $teble_header."\r";
        foreach ($list as $row){
            foreach($index as $val){
                $strexport.=$row[$val]."\t";
            }
            $strexport.="\r";

        }
        $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);
        exit($strexport);
    }


}
