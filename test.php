
<?php

$a = ['name','age','sex'];
$b = ['zxc',54,'男'];
$c = array_combine($a,$b);
$d = ['1','2','4','4','4','5','5','6'];
//print_r($c);

$count = array_count_values($d);

////print_r($count);

$arr1 = array('a'=>'PHP');
$arr2 = array('a'=>'JAVA');
//如果键名为字符，且键名相同，array_merge()后面数组元素值会覆盖前面数组元素值
//print_r(array_merge($arr1,$arr2)); //Array ( [a] => JAVA )
//如果键名为字符，且键名相同，数组相加会将最先出现的值作为结果
//print_r($arr1+$arr2); //Array ( [a] => PHP )

$a=array("red"=>'1111',"green"=>'2222');
array_push($a,"blue","yellow");
//$a = array_reverse($a);
//print_r($a);

$rand = array_search('11111',$a);
//print_r($rand);

$a1=array("0"=>"red","1"=>"green");
$a2=array("0"=>"purple","1"=>"orange");
array_splice($a1,2,0,$a2);
//print_r($a1);

$sites = array("Google", 'nihao' => "Runoob", "Taobao", "Facebook");

if (in_array("Runoob", $sites))
{
    //echo "找到匹配项！";
}
else
{
    //echo "没有找到匹配项！";
}

$my_array = array("Dog","Cat","Horse");

list($a, , $c) = $my_array;
//echo "Here I only use the $a and $c variables.";

//print_r(microtime ());
/*$time = explode (" ", microtime () );
print_r($time[1].'<br>');
$millisecond =   $time [0] * 1000 < 100?$time [0] * 1000 + 100:$time [0] * 1000;
print_r($millisecond.'<br>');
$time = $time [1] .$millisecond;
print_r($time);
$time2 = explode ( ".", $time );
$time = $time2 [0];
print_r($time);*/

$date = strtotime(date("Ymd000000").'+2days');

//echo $date;

/*//初始化
$ch = curl_init();
//设置选项，包括URL
curl_setopt($ch, CURLOPT_URL, "http://www.baidu.com");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
//执行并获取HTML文档内容
$output = curl_exec($ch);
//释放curl句柄
curl_close($ch);
//打印获得的数据
print_r($output);*/



/*print(time());
echo "<br>";
print(date("Ymd115959"));
echo "<br>";
print(strtotime(date("Ymd235959"))-time());
die;*/
/*$titlename = 'nihao';
$title = 'hehe';
$filename = 'zhang';
$datas = [
    'haha' =>[1,2,3],
    'hehe' =>[4,5,6],
    'heihei' =>[7,8,9]
];
$str = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"\r\nxmlns:x=\"urn:schemas-microsoft-com:office:excel\"\r\nxmlns=\"http://www.w3.org/TR/REC-html40\">\r\n<head>\r\n<meta http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n</head>\r\n<body>";
$str .="<table border=1><head>".$titlename."</head>";
$str .= $title;
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
header( "Content-Type: application/vnd.ms-excel; name='excel'" );
header( "Content-type: application/octet-stream" );
header( "Content-Disposition: attachment; filename=".$filename );
header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header( "Pragma: no-cache" );
header( "Expires: 0" );
exit( $str );*/


/*unction microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();
usleep(100);
$time_end = microtime_float();

$time = $time_end - $time_start;

echo "Did nothing in $time seconds\n";

print(microtime());*/

//print_r(* 100);die;

//print_r(count([1,2,3]));die;

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
function exportExcel($list,$filename,$indexKey,$startRow=1,$excel2007=false){
    //文件引入
    require_once BASEPATH.'/libraries/excel/PHPExcel.php';
    require_once BASEPATH.'/libraries/excel/PHPExcel/Writer/Excel2007.php';

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

    //接下来就是写数据到表格里面去
    $objActSheet = $objPHPExcel->getActiveSheet();
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



//phpinfo();

/*$link = ("localhost","root","123");
if(!$link) echo "FAILD!连接错误，用户名密码不对";
else echo "OK!可以连接";*/
phpinfo();










?>