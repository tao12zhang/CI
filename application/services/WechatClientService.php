<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**微信
 * Created by PhpStorm.
 * User: XFour
 * Date: 17/7/19
 * Time: 下午2:39
 */

class WechatClientService extends MY_Service {


    private $mediaName = 'wechatMediaImg';
    private $mediaId = 'wechatMedia';
    private $MaterialId = 'wechatMaterial';
    private $wechatMedia = 'wechatMediaUrl';
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('Common');
        $weiChatConf = $this->config->item('weChat');
        $this->wechatToken = $weiChatConf['token'];
        $this->appid = $weiChatConf['appid'];
        $this->secret = $weiChatConf['secret'];
        $this->sendTemplateMsg = $weiChatConf['sendTemplateMsg'];
        $this->getAccessToken = $weiChatConf['getAccessToken'];
        $this->customSend = $weiChatConf['customSend'];
        $this->mediaUpload = $weiChatConf['mediaUpload'];
        $this->addMaterial = $weiChatConf['addMaterial'];
        $this->createMenu = $weiChatConf['createMenu'];
        $this->logPath =$this->config->item('log_path');
        $this->load->model('WechatModel');
        $this->load->library('Redis_db');
        $this->redis_db->connect();
    }
    
    // ------------- 七夕活动 redis 操作 ------------
    
    public function getQrMediaId(){
        return $this->redis_db->getRedis()->get($this->mediaId."_image_exchange");
    }

        /**
     * 保存用户昵称
     * @param type $openId
     * @param type $nick
     */
    public function setNick($openId,$nick){
        $key_nick = 'user:nick:'.$openId;
        $this->redis_db->getRedis()->set($key_nick,$nick);
    }
    /**
     * 取得用户昵称
     * @param type $openId
     */
    public function getNick($openId){
        $key_nick = 'user:nick:'.$openId;
        return $this->redis_db->getRedis()->get($key_nick);
    }

    /**
     * 设置此用户当前步骤
     * @param type $openId
     * @param type $step
     */
    public function setStep($openId,$step){
        $key = 'activite:qixi:step:string:'.$openId;
        $timeout = 60*60*24; // 1天
        $this->redis_db->getRedis()->set($key,$step,$timeout);
    }
    /**
     * 取得此用户当前步骤
     * @param type $openId
     * @return type
     */
    public function getStep($openId){
        $key = 'activite:qixi:step:string:'.$openId;
        return $this->redis_db->getRedis()->get($key);
    }
    /**
     * 保存用户输入的故事
     * @param type $openId
     * @param type $fields
     */
    public function saveStory($openId, $fields){
        $key = 'activite:qixi:story:hash:'.$openId;
        $this->redis_db->getRedis()->hMset($key, $fields);
    }
    /**
     * 取得某个用户的故事
     * @param type $openId
     * @return type
     */
    public function getStory($openId){
        $key = 'activite:qixi:story:hash:'.$openId;
        return $this->redis_db->getRedis()->hgetall($key);
    }
    
    public function delStory($openId){
        $key = 'activite:qixi:story:set';
        $this->redis_db->getRedis()->sRem($key, $openId);
    }

    /**
     * 把故事添加到集合,记录这词活动所有的故事
     * @param type $openId
     */
    public function addStorySet($openId){
        $key = 'activite:qixi:story:set';
        $this->redis_db->getRedis()->sAdd($key, $openId);
    }
    /**
     * 显示故事集合中的所有故事
     * @return type
     */
    public function showStorySet(){
        $key = 'activite:qixi:story:set';
        return $this->redis_db->getRedis()->sMembers($key);
    }
    public function getStoryCount(){
        $key = 'activite:qixi:story:set';
        return $this->redis_db->getRedis()->sSize($key);
    }

    public function getKey($key){
        return $this->redis_db->getRedis()->get($key);
    }
    
    public function getKeys(){
        return $this->redis_db->getRedis()->keys('activite:qixi:*');
    }
    public function keys($key){
        return $this->redis_db->getRedis()->keys($key);
    }
    public function delkey($key){
        $this->redis_db->getRedis()->delete($key);
    }
    /**
     * 从故事集合中随机选择一个
     * @return type
     */
    public function randomSelectStory(){
        $key = 'activite:qixi:story:set'; 
        return $this->redis_db->getRedis()->sRandMember($key);
    }

    /**
     * 判断此用户是否读过另一个用户的故事
     * @param type $openId
     * @param type $otherId
     */
    public function isReaded($openId, $otherId){
        $key_myReadedSet = 'activite:qixi:storyreaded:set:'. $openId;
        return $this->redis_db->getRedis()->sIsMember($key_myReadedSet, $otherId);
    }
    
    /**
     * 记录此用户读过的故事
     * @param type $openId
     * @param type $otherId
     */
    public function addReadedSet($openId, $otherId){
        $key_myReadedSet = 'activite:qixi:storyreaded:set:'. $openId;
        $this->redis_db->getRedis()->sAdd($key_myReadedSet, $otherId);
    }
    /**
     * 记录此用户正在读谁的故事
     */
    public function setReadingWhose($openId, $otherId){
        $key = 'activite:qixi:storyreading:string:'.$openId;
        $timeout = 60*60*24; // 1天
        $this->redis_db->getRedis()->set($key,$otherId,$timeout);
    }
    /**
     * 取得正在读谁的故事
     * @param type $openId
     */
    public function getReadingWhose($openId){
        $key = 'activite:qixi:storyreading:string:'.$openId;
        return $this->redis_db->getRedis()->get($key);
    }
    /**
     * 保存此用户给好友的留言
     * @param type $openId
     * @param type $msg
     */
    public function saveLeaveMsg($openId, $msg){
        $key = 'activite:qixi:friendmsg:hash:'. $openId;
        $timeout = 60*60*24; // 1天
        $this->redis_db->getRedis()->set($key,$msg,$timeout);
    }
    public function getLeaveMsg($openId){
        $key = 'activite:qixi:friendmsg:hash:'. $openId;
        return $this->redis_db->getRedis()->get($key);
    }
    /**
     * 保存给好友的留言的类型
     * @param type $openId
     * @param type $type
     */
    public function setLeaveMsgType($openId, $type){
        $key = 'activite:qixi:friendmsg:type:'. $openId;
        $timeout = 60*60*24; // 1天
        $this->redis_db->getRedis()->set($key,$type,$timeout);
    }
    public function getLeaveMsgType($openId){
        $key = 'activite:qixi:friendmsg:type:'. $openId;
        return $this->redis_db->getRedis()->get($key);
    }

    /**
     * 消息入队
     * @param type $msg
     */
    public function inQueue($msg){
        $key_queue = 'activite:qixi:msgqueue:list';
        $this->redis_db->getRedis()->lPush($key_queue, $msg);
    }
    /**
     * 消息出队
     * @return type
     */
    public function outQueue(){
        $key_queue = 'activite:qixi:msgqueue:list';
        return $this->redis_db->getRedis()->rPop($key_queue);
    }
    /**
     * 查看队列中的所有消息
     * @return type
     */
    public function showQueue(){
        $key_queue = 'activite:qixi:msgqueue:list';
        return $this->redis_db->getRedis()->lRange($key_queue, 0, -1);
    }
    

    /**
     * 队列中消息的数量
     * @return type
     */
    public function sizeQueue(){
        $key_queue = 'activite:qixi:msgqueue:list';
        return $this->redis_db->getRedis()->lSize($key_queue);
    }

    // ------------- 七夕活动 redis 操作 end ------------

    public function test(){
        return 'b';
    }
    //验证签名
    public function checkSignature($echoStr,$signature,$timestamp,$nonce)
    {
        $tmpArr     = array($this->wechatToken, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr     = implode($tmpArr);
        $tmpStr     = sha1($tmpStr);
        if($tmpStr == $signature){
            return $echoStr;
        }
        return false;
    }
    public function getAccessToken(){

        $this->load->library("Redis_db");//实例化redis对象
        $redisKey = 'IDS_WX_ACCESS_TOKEN';
        $accessToken = $this->redis_db->getRedis()->get($redisKey);

        if(!empty($accessToken)){
            return $accessToken;
        }
        
        
        $aceccParams = array(
            'grant_type'=>'client_credential',
            'appid'=>$this->appid,
            'secret'=>$this->secret,

        );
        $accessData = curlHttpClient($this->getAccessToken,$aceccParams,'GET');
        //file_put_contents("/tmp/debug".date("Ymd").".log", "time:   ".date("His")."\n      accessToke:   ".var_export($accessData,true)."\n", FILE_APPEND);
        $acess = json_decode($accessData,true);
        if(isset($acess['access_token']) && !empty($acess['access_token'])){
            $this->redis_db->getRedis()->set($redisKey,$acess['access_token'],$acess['expires_in']-10);
            return $acess['access_token'];
        }
        $this->status = false;
        return null;

    }

    public function  mediaUpload($type,$name){
        if(empty($type)|| empty($name)){
            return false;
        }

        $type = strtolower($type);
        $name = strtolower($name);
        $media = $this->redis_db->getRedis()->get($this->wechatMedia."_{$type}_{$name}");

        if(empty($media)){
            return false;
        }

        $mediaUpload = $this->mediaUpload;
        $accessToken = $this->getAccessToken();
        if(empty($accessToken)){
            return false;
        }
        $url = $mediaUpload."?access_token={$accessToken}&type={$type}";
        switch ($type){
            case 'image':
                $pathInfo = pathinfo($media);
                //$extension  = end(explode('.',$media));
                $savePath = $this->logPath."/{$this->mediaId}_{$type}_{$name}.{$pathInfo['extension']}";
                $content = file_get_contents($media);
                file_put_contents($savePath, $content);
                $result = curlHttpClient($url, $savePath, $method = 'POST', [],  [], true);
                if($data = json_decode($result,true)){
                    if(isset($data['media_id']) && !empty($data['media_id'])){
                        $this->redis_db->getRedis()->set($this->mediaId."_{$type}_{$name}",$data['media_id']);
                        return $data['media_id'];
                    }
                }
                return false;
            default:
                return false;
        }

    }
    public function  materialUpload($type,$name){
        if(empty($type)|| empty($name)){
            return false;
        }

        $type = strtolower($type);
        $name = strtolower($name);
        $media = $this->redis_db->getRedis()->get($this->wechatMedia."_{$type}_{$name}");

        if(empty($media)){
            return false;
        }

        $mediaUpload = $this->addMaterial;
        $accessToken = $this->getAccessToken();
        if(empty($accessToken)){
            return false;
        }
        $url = $mediaUpload."?access_token={$accessToken}&type={$type}";
        switch ($type){
            case 'image':
                $pathInfo = pathinfo($media);
                //$extension  = end(explode('.',$media));
                $savePath = $this->logPath."/{$this->MaterialId}_{$type}_{$name}.{$pathInfo['extension']}";
                $content = file_get_contents($media);
                file_put_contents($savePath, $content);
                $result = curlHttpClient($url, $savePath, $method = 'POST', ['uploadKey'=>'media'],  [], true);
                //echo $result;
                if($data = json_decode($result,true)){
                    if(isset($data['media_id']) && !empty($data['media_id'])){
                        $this->redis_db->getRedis()->set($this->MaterialId."_{$type}_{$name}",$data['media_id']);
                        return $data['media_id'];
                    }
                }
                return false;
            default:
                return false;
        }

    }

    public function saveMedia($media,$type,$name){
        if(empty($media) || empty($type) || empty($name)){
            return false;
        }
        $type = strtolower($type);
        $name = strtolower($name);
        $this->redis_db->getRedis()->set($this->wechatMedia."_{$type}_{$name}",$media);
        return true;

    }

    public function createMenu(){
        $teamWorkId = $this->redis_db->getRedis()->get($this->MaterialId."_image_teamwork");
        $exchangeId = $this->redis_db->getRedis()->get($this->MaterialId."_image_exchange");
        $shopId     = $this->redis_db->getRedis()->get($this->MaterialId."_image_shop");

        $menu = [
            'button'=>[
                //[
                //    'type'=>'view',
                //    'name'=>'大眼睛商店',
                //    'url'=>'https://h5.youzan.com/v2/showcase/homepage?alias=14566ezsy',
                //],
                [
                    'type'=>'media_id',
                    'name'=>'交换活动',
                    'media_id'=>$exchangeId,
                ],
                [
                    'type'=>'media_id',
                    'name'=>'买买买商店',
                    'media_id'=>$shopId,
                ],
                [
                    'type'=>'media_id',
                    'name'=>'合作联系',
                    'media_id'=>$teamWorkId,
                ],
            ]

        ];
        echo  json_encode($menu,JSON_UNESCAPED_UNICODE);
        $mediaUpload = $this->createMenu;
        $accessToken = $this->getAccessToken();
        if(empty($accessToken)){
            return 'token获取失败';
        }
        $url = $mediaUpload."?access_token={$accessToken}";
        $result = curlHttpClient($url, json_encode($menu,JSON_UNESCAPED_UNICODE));

        return $result;


    }

    public function delMenu(){
        $accessToken = $this->getAccessToken();
        if(empty($accessToken)){
            return 'token获取失败';
        }
        $params = ['access_token'=>$accessToken];
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete";
        $result = curlHttpClient($url,$params,"GET");
        return $result;


    }

    //响应消息
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $this->logger("R \r\n".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            if (($postObj->MsgType == "event") && ($postObj->Event == "subscribe" || $postObj->Event == "unsubscribe")){
                //过滤关注和取消关注事件
            }else{

            }

            //消息类型分离
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "image":
                    $result = $this->receiveImage($postObj);
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $result = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
            $this->logger("T \r\n".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

    //接收事件消息
    public function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                $content = "欢迎关注 ";
                $content .= (!empty($object->EventKey))?("\n来自二维码场景 ".str_replace("qrscene_","",$object->EventKey)):"";
                break;
            case "unsubscribe":
                $content = "取消关注";
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "COMPANY":
                        $content = array();
                        $content[] = array("Title"=>"大眼睛", "Description"=>"", "PicUrl"=>"", "Url" =>"");
                        break;
                    default:
                        $content = "点击菜单：".$object->EventKey;
                        break;
                }
                break;
            case "VIEW":
                $content = "跳转链接 ".$object->EventKey;
                break;
            case "SCAN":
                $content = "扫描场景 ".$object->EventKey;
                break;
            case "LOCATION":
                $content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
                break;
            case "scancode_waitmsg":
                if ($object->ScanCodeInfo->ScanType == "qrcode"){
                    $content = "扫码带提示：类型 二维码 结果：".$object->ScanCodeInfo->ScanResult;
                }else if ($object->ScanCodeInfo->ScanType == "barcode"){
                    $codeinfo = explode(",",strval($object->ScanCodeInfo->ScanResult));
                    $codeValue = $codeinfo[1];
                    $content = "扫码带提示：类型 条形码 结果：".$codeValue;
                }else{
                    $content = "扫码带提示：类型 ".$object->ScanCodeInfo->ScanType." 结果：".$object->ScanCodeInfo->ScanResult;
                }
                break;
            case "scancode_push":
                $content = "扫码推事件";
                break;
            case "pic_sysphoto":
                $content = "系统拍照";
                break;
            case "pic_weixin":
                $content = "相册发图：数量 ".$object->SendPicsInfo->Count;
                break;
            case "pic_photo_or_album":
                $content = "拍照或者相册：数量 ".$object->SendPicsInfo->Count;
                break;
            case "location_select":
                $content = "发送位置：标签 ".$object->SendLocationInfo->Label;
                break;
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }

        if(is_array($content)){
            if (isset($content[0]['PicUrl'])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }

    //接收文本消息
    public function receiveText($object)
    {
        $keyword = trim($object->Content);
        //多客服人工回复模式
        if (strstr($keyword, "请问在吗") || strstr($keyword, "在线客服")){
            $result = $this->transmitService($object);
            return $result;
        }

        //自动回复模式
        if (strstr($keyword, "文本")){
            $content = "这是个文本消息";
        }else if (strstr($keyword, "表情")){
            $content = "中国：".$this->bytes_to_emoji(0x1F1E8).$this->bytes_to_emoji(0x1F1F3)."\n仙人掌：".$this->bytes_to_emoji(0x1F335);
        }else if (strstr($keyword, "单图文")){
            $content = array();
            $content[] = array("Title"=>"单图文标题",  "Description"=>"单图文内容", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
        }else if (strstr($keyword, "图文") || strstr($keyword, "多图文")){
            $content = array();
            $content[] = array("Title"=>"多图文1标题", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
            $content[] = array("Title"=>"多图文2标题", "Description"=>"", "PicUrl"=>"http://d.hiphotos.bdimg.com/wisegame/pic/item/f3529822720e0cf3ac9f1ada0846f21fbe09aaa3.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
            $content[] = array("Title"=>"多图文3标题", "Description"=>"", "PicUrl"=>"http://g.hiphotos.bdimg.com/wisegame/pic/item/18cb0a46f21fbe090d338acc6a600c338644adfd.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
        }else if (strstr($keyword, "音乐")){
            $content = array();
            $content = array("Title"=>"最炫民族风", "Description"=>"歌手：凤凰传奇", "MusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3", "HQMusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3");
        }else{
            $content = date("Y-m-d H:i:s",time())."\nOpenID：".$object->FromUserName."\n技术支持 idsdayanjing";
        }

        if(is_array($content)){
            if (isset($content[0])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }

    //接收图片消息
    public function receiveImage($object)
    {
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    //接收位置消息
    public function receiveLocation($object)
    {
        $content = "你发送的是位置，经度为：".$object->Location_Y."；纬度为：".$object->Location_X."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收语音消息
    public function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "你刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }
        return $result;
    }

    //接收视频消息
    public function receiveVideo($object)
    {
        $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
        $result = $this->transmitVideo($object, $content);
        return $result;
    }

    //接收链接消息
    public function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //回复文本消息
    public function transmitText($object, $content)
    {
        if (!isset($content) || empty($content)){
            return "";
        }

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);

        return $result;
    }

    //回复图文消息
    public function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return "";
        }
        $itemTpl = "        <item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
        </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    <ArticleCount>%s</ArticleCount>
    <Articles>
$item_str    </Articles>
</xml>";
//echo 'FromUserName:'.$object->FromUserName.'---ToUserName: '. $object->ToUserName.' ----time:'.time().'--- count: '.count($newsArray);
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    public function transmitMusic($object, $musicArray)
    {
        if(!is_array($musicArray)){
            return "";
        }
        $itemTpl = "<Music>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <MusicUrl><![CDATA[%s]]></MusicUrl>
        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
    </Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[music]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复图片消息
    public function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
        <MediaId><![CDATA[%s]]></MediaId>
    </Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[image]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复语音消息
    public function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
        <MediaId><![CDATA[%s]]></MediaId>
    </Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[voice]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复视频消息
    public function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
        <MediaId><![CDATA[%s]]></MediaId>
        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
    </Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[video]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复多客服消息
    public function transmitService($object)
    {
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复第三方接口消息
    public function relayPart3($url, $rawData)
    {
        $headers = array("Content-Type: text/xml; charset=utf-8");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //字节转Emoji表情
    function bytes_to_emoji($cp)
    {
        if ($cp > 0x10000){       # 4 bytes
            return chr(0xF0 | (($cp & 0x1C0000) >> 18)).chr(0x80 | (($cp & 0x3F000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else if ($cp > 0x800){   # 3 bytes
            return chr(0xE0 | (($cp & 0xF000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else if ($cp > 0x80){    # 2 bytes
            return chr(0xC0 | (($cp & 0x7C0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else{                    # 1 byte
            return chr($cp);
        }
    }

    //日志记录
    private function logger($log_content)
    {

        $max_size = 1000000;
        $log_filename = $this->logPath."WXaccess_".date("Ymd").".log";
        if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
        file_put_contents($log_filename, date('Y-m-d H:i:s')." ".$log_content."\r\n", FILE_APPEND);

    }
}
?>