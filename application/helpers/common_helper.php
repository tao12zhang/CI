<?php
if (! function_exists('access_log'))
{
    function acceess_log()
    {
        $_log = & load_class('log');
        $_log->set_threshold();
        $_log->write_log('INFO',$message);
    }
}

if (!function_exists('getOnlineStatus'))
{
    //1是上线,2是待上线,3是已下线
    function getOnlineStatus($startTime,$endTime,$now)
    {
        if($startTime<$now && $endTime>$now){
            return 1;
        }else if($startTime>$now){
            return 2;
        }else if($endTime<$now){
            return 3;
        }
        return 0;
    }
}

if (!function_exists('getMillisecond'))
{
    //返回字符串的毫秒数时间戳
    function getMillisecond()
    {
        $time = explode (" ", microtime ());
        $millisecond = $time[0]*1000<100?$time[0]*1000+100:$time[0]*1000;
        $time = $time[1].$millisecond;
        $time2 = explode(" ",$time);
        $time = $time2[0];
        return (int)$time;
    }
}

if(!function_exists('getFullImageUrl'))
{
    //根据资源路径计算出完整的图片地址
    function getFullImageUrl($uri)
    {
        if(empty($uri)){
            return '';
        }
        $CI = get_instance();
        $CI->load->config('apiConfig');
        $CDNConf = $CI->config->item('cdn');
        $url = $CDNConf['host'].':'.$CDNConf['port'].'/cd/'.$uri;
        $result = curlHttpClient($url,[],'GET');
        if(is_string($result) && strpos($result,$uri)){
            return $result;
        }
        return '';

    }
}

if (!function_exists('curlHttpClient'))
{
    function curlHttpClient($url,$params,$method = 'POST',$option = array(),$header = array(),$multi = false )
    {
        $option['timeout'] = isset($option['timeout'])? $option['timeout'] : 60;
        $option['uploadKey'] = isset($option['uploadKey'])? $option['uploadKey'] : 'file';
        $cookie = isset($header['Cookie'])? $header['Cookie'] : '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_COOKIE , $cookie );
        curl_setopt($ch, CURLOPT_TIMEOUT, $option['timeout']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //根据请求类型设置特定参数
        switch (strtoupper($method)) {
            case 'GET':
                $url = $url . '?' . http_build_query($params);
                curl_setopt($ch,CURLOPT_URL,$url);
                break;
            case 'POST':
                if ($multi) {
                    $data = array(
                        $option['uploadKey'] => new CURLFile($params),//php版本>=5.5
                    );
                }else{
                    $data = $params;
                }
                //判断是否传输文件
                curl_setopt($ch, CURLOPT_POST,1);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                return '不支持的请求方式!';
        }

        //初始化并执行curl请求
        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if (!$result || $error) {
            return '请求发生错误:' . $error;
        }

        return $result;
    }
}









