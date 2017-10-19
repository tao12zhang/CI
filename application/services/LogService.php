<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**微信
 * Created by PhpStorm.
 * User: XFour
 * Date: 17/7/19
 * Time: 下午2:39
 */

class LogService extends MY_Service {


    public function __construct()
    {
        parent::__construct();
        $this->load->library('Redis_db');
        $this->redis_db->connect();
        $this->logqueue = 'log:test:'.date("Ymd",time());
    }
    
    public function in($msg){
        $this->redis_db->getRedis()->lPush($this->logqueue, '[ '.date("Y-m-d h:i:s",time()).' ] ' . $msg);
        
        // 为日志队列设置过期时间（一天）
        $ttl = $this->redis_db->getRedis()->ttl($this->logqueue);
        if(empty($ttl) || $ttl < 1){
            $this->redis_db->getRedis()->setTimeout($this->logqueue, 60*60*24);
        }
        
        return $this->redis_db->getRedis()->ttl($this->logqueue);
    }

    public function show(){
        return $this->redis_db->getRedis()->lRange($this->logqueue, 0, -1);
    }
    
    public function size(){
        return $this->redis_db->getRedis()->lSize($this->logqueue);
    }
    
    public function clear(){
        $this->redis_db->getRedis()->delete($this->logqueue);
    }
}
?>