
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

//初始化
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
print_r($output);





?>