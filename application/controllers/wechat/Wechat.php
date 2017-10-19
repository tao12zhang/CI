<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 微信公众号接口基础控制器类
 * Created by PhpStorm.
 * User: XFour
 * Date: 17/7/19
 * Time: 上午11:48
 */
class Wechat extends MY_Controller {


    private $postObj = null;

    public function __construct(){
        parent::__construct();
        $this->load->service('WechatSearchService');
        $this->load->service('WechatClientService');
        $this->load->service('LogService');
        $this->CreateTime = time();

        //验证参数
        $this->_init();

    }

    public function _init(){
        //验证参数
        $this->check();

    }

    public function check(){
        if(isset($GLOBALS["HTTP_RAW_POST_DATA"]) && !empty($GLOBALS["HTTP_RAW_POST_DATA"])){
            $this->postObj = simplexml_load_string($GLOBALS["HTTP_RAW_POST_DATA"], 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        $this->prams['get'] = $this->input->get();
        $this->prams['post'] = $this->input->post();


    }
    public function valid()
    {
        $echoStr        = $this->input->get("echostr");
        $signature      = $this->input->get("signature");
        $timestamp      = $this->input->get("timestamp");
        $nonce          = $this->input->get("nonce");

        $result = $this->WechatClientService->checkSignature($echoStr,$signature,$timestamp,$nonce);
        echo $result;
        exit;
    }

    public function sendKFmsg(){
//        $this->dump($this->WechatClientService->appid);
//        $this->dump($this->WechatClientService->secret);
        $accessToken = $this->WechatClientService->getAccessToken();
//        $this->dump($accessToken);

        // 从队列中取消息
        $msg = $this->WechatClientService->outQueue();
//        $this->log('---sendKFmsg, msg:' . $msg);

        $msgObj = json_decode($msg, true);

        $touser = $msgObj['touser'];
        $msgtype = $msgObj['msgtype'];

        if($msgtype == 'text'){
            $contentStr=urlencode($msgObj['content']);
            $a=array("content"=>"{$contentStr}");
            $b=array("touser"=>$touser,"msgtype"=>$msgtype,"text"=>$a);
        }else if($msgtype == 'image'){
            $a=array("media_id"=>"{$msgObj['mediaId']}");
            $b=array("touser"=>$touser,"msgtype"=>$msgtype,"image"=>$a);
        }else if($msgtype == 'voice'){
            $a=array("media_id"=>"{$msgObj['mediaId']}");
            $b=array("touser"=>$touser,"msgtype"=>$msgtype,"voice"=>$a);
        }

        $post=json_encode($b);
        $post=urldecode($post);

        $posturl="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$accessToken}";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$posturl);//url
        curl_setopt($ch,CURLOPT_POST,1);//POST
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_exec($ch);
        curl_close($ch);
    }

    public function dump($vars){
        echo "<div align=left><pre>\n" . htmlspecialchars(print_r($vars, true)) . "\n</pre></div>\n";
    }

    public function test(){
        echo 'hi';
        $this->log('--- test');



        $openId = 'oYNBkwiydRw-cVagb95NrSej4E18';

        // 删除某个故事
//        $targetid = 'oYNBkwr9oE-D8Kiodn1aYw45h2A8';
//        $this->WechatClientService->delStory($targetid);

//        $otherid = 'oYNBkwtELtfHGGIxHJnQ1aFS80r0';
//        $otheridstr = md5($otherid);
//        $this->dump('$otheridstr: '.$otheridstr);
//        $this->dump($this->WechatClientService->getStory($otheridstr));
//        $this->dump($this->WechatClientService->getNick($otheridstr));


        // 发送结束二维码
//        $data = array('touser'=>$openId, 'msgtype'=>'image',
//            'mediaId'=>'eJyjorr2g59wcuwk95dFN5Vi7Y8eydh7R0l2xBy3tWalFWlU5mLcLRrXzVrFdhf6' );

//        $this->WechatClientService->inQueue(json_encode($data));

        // 删除队列
//        $this->WechatClientService->delkey('activite:qixi:msgqueue:list');

        // 查看故事集合
//        $set_story = $this->WechatClientService->showStorySet();
//        for($i=0; $i<count($set_story); $i++){
//            $openid = $set_story[$i];
//            $openId_str = md5($openid);
//            echo ("$i, openid:{$openid}"."<br/>");
//            echo ("$i, nick:". $this->WechatClientService->getNick($openId_str)."<br/>");
//            $story = $this->WechatClientService->getStory($openId_str);
//            echo ("$i, story:". $story['Content']."<br/>");
//            echo "-----"."<br/>";
//        }

        // 删除故事集合
//        $this->WechatClientService->delkey('activite:qixi:story:set');

//        $this->WechatClientService->inQueue('test in queue');

        // 查看队列
//        $queue = $this->WechatClientService->showQueue();
//        $size = $this->WechatClientService->sizeQueue();
//        echo 'queue:'.$size;
//        $this->dump($queue);


        // 发送客服消息
        /*
        $accessToken = $this->WechatClientService->getAccessToken();

        $this->log('--- '.$accessToken);

        $touser = $openId;
        $msgtype = 'image';

        if($msgtype == 'text'){
//            $contentStr=urlencode($msgObj['content']);
//            $a=array("content"=>"{$contentStr}");
//            $b=array("touser"=>$touser,"msgtype"=>$msgtype,"text"=>$a);
        }else if($msgtype == 'image'){
            $mid = $this->WechatClientService->getQrMediaId();
            $this->log('mid:'.$mid);
            $a=array("media_id"=>$mid);
            $b=array("touser"=>$touser,"msgtype"=>$msgtype,"image"=>$a);
        }else if($msgtype == 'voice'){
//            $a=array("media_id"=>"{$msgObj['mediaId']}");
//            $b=array("touser"=>$touser,"msgtype"=>$msgtype,"voice"=>$a);
        }

        $post=json_encode($b);
        $post=urldecode($post);

        $posturl="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$accessToken}";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$posturl);//url
        curl_setopt($ch,CURLOPT_POST,1);//POST
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_exec($ch);
        curl_close($ch);

        */


//        $this->WechatClientService->addStorySet($openId);
//
//       // 当前step
//       $step = $this->WechatClientService->getStep($openId);
//       print_r('---step:');
//       print_r($step);
//
//       // 故事
//       $story = $this->WechatClientService->getStory($openId);
//       print_r('---story:');
//       print_r($story);
//
//       // 故事集合
//       $story_set = $this->WechatClientService->showStorySet();
//       print_r('---story_set:');
//       print_r($story_set);


//        $this->WechatClientService->setR();
//        $ret = $this->WechatClientService->getR();
//        $this->log('getR:' . $ret);


//        $msg = ['text'=>['content'=>'测试3'.time()]];
//        $data = $this->getCustomMsgData('oYNBkwiydRw-cVagb95NrSej4E18', 'text', $msg);
//        $result = $this->customSend(json_encode($data  ,JSON_UNESCAPED_UNICODE));
//
//        $this->log('--- '.$result);

    }

    public function getNick($openId){
        $openId_str = md5($openId);
        $this->log('--- getNick $openId_str:' . $openId_str);
        $nick = $this->WechatClientService->getNick($openId_str);
        $this->log('--- getNick $nick:' . $nick);
        if(!empty($nick)){
            return $nick;
        }

        $accessToken = $this->WechatClientService->getAccessToken();
        $this->log('token:'. $accessToken);
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$accessToken.
            '&openid='.$openId.'&lang=zh_CN';

        $result = $this->gethttp($url);
        $info = json_decode($result, true);
        $this->log('--- getNick $info:' . $info);
        $this->WechatClientService->setNick($openId_str, $info['nickname']);
        return $info['nickname'];

    }

    public function log($msg){
        $this->LogService->in($msg);
    }

    public function gethttp($url){
        //初始化
        $ch = curl_init();

        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //执行并获取HTML文档内容
        $output = curl_exec($ch);

        //释放curl句柄
        curl_close($ch);

        //打印获得的数据
        return $output;
    }

    public function distribute(){
        $this->log('=== 1');
        if($this->input->get("echostr")){
            $this->valid();
        }
        if(empty($this->postObj)){
            echo 'empty prams';
            exit;
        }
        $this->log('=== 2');

        $fromuser = '';
        if (($this->postObj->MsgType == "event") && ($this->postObj->Event == "subscribe" || $this->postObj->Event == "unsubscribe")){
            //过滤关注和取消关注事件
        }else{
            //消息类型分离
            switch (trim($this->postObj->MsgType))
            {

                case "text":
                    $result = $this->receiveText($this->postObj);
                    break;

                // 语音
                case "voice":
                    $result = $this->receiveVoice($this->postObj);
//                    $result = $this->WechatClientService->getAccessToken();
//                    $result = $this->WechatClientService->transmitText($this->postObj, $this->postObj->MediaId .
//                            ' , ' . $fromuser);
                    break;

                // 图片
                case "image":
                    $result = $this->receiveImage($this->postObj);
//                    $result = $fromuser;
//                    $result = $this->WechatClientService->transmitText($this->postObj,
//                            $this->postObj->MediaId . ', ' . $this->postObj->PicUrl . ', ' . $this->postObj->FromUserName);
                    break;

                //过滤关注和取消关注事件
                case "event":

                case "subscribe":

                case "unsubscribe":
                    $result = '';
                    break;
                default:
                    $result = "unknown msg type: ".$this->postObj->MsgType;
                    break;
            }
            echo $result;

            exit();
        }

    }

    //接收文本消息
    public function receiveText($object)
    {
        $pramas = (array)$object;
        $result = '欢迎访问';

        $from = $pramas['FromUserName'];
        $from_str = md5($from);
        $txt = $pramas['Content'];
        $type = $pramas['MsgType'];
        $createTime = $pramas['CreateTime'];
        $currentStep = $this->WechatClientService->getStep($from_str);

        $this->log('receiveText, txt:' . $txt . ', from:' . $from . ', currentStep:' . $currentStep);

        //--- 处理举报
        if($txt == '举报'){
            // 返回的提示信息
            $result = "你好,已经收到你的举报,我们会尽快处理";

            $this->WechatClientService->setStep($from_str, 0);
            // 返回提示信息
            return $this->WechatClientService->transmitText($object,  $result);
        }

        // --- 开始互动
        if($this->activity_keywords() == $txt || ($currentStep == 2 && '2' == $txt)){
            $nick = $this->getNick($from);
            // 返回的提示信息
            $result = $this->activity_tipInfo($nick);

            // 记录此用户的 step
            $this->WechatClientService->setStep($from_str, 1);

            // 返回提示信息
            return $this->WechatClientService->transmitText($object,  $result);
//            return '';
        }

        // --- 接收故事
        if($currentStep == 1){
            // 返回的提示信息
            $result = $this->activity_exchangeConfirmInfo();

            // 保存故事
            $story = array('CreateTime'=>$createTime,
                'MsgType'=>$type,
                'Content'=>$txt);
            $this->WechatClientService->saveStory($from_str, $story);

            // 放入故事集合
            $this->WechatClientService->addStorySet($from);

            // 记录step
            $this->WechatClientService->setStep($from_str, 2);

            // 返回提示信息
            return $this->WechatClientService->transmitText($object,  $result);
//            return '';
        }

        // --- 确认交换
        if($currentStep == 2 && $txt == '1'){
            // 返回的提示信息
            $result = "收到，交换正在进行";

            // 随机取得一个别人的故事
            $otherId = $this->WechatClientService->randomSelectStory();

            while($otherId == $from){
                $otherId = $this->WechatClientService->randomSelectStory();
            }
            $otherId_str = md5($otherId);
            $this->log('$from:'.$from);
            $this->log('$otherId:'.$otherId);
            $this->log('$otherId_str2:'.$otherId_str);

            // 构造消息“来自于小戈的粉丝@xxx 与你交换七夕爱情故事“, 放入队列
            $otherNick = $this->WechatClientService->getNick($otherId_str);

            $this->LogService->in('get story, $otherNick:'.$otherNick);

            $msg_tip = ["touser"=>$from, 'msgtype'=>'text',
                'content'=>'来自大眼睛的粉丝@'.$otherNick.' 与你交换' . $this->activityKeyWords];
            $msg_str = json_encode($msg_tip);
            $this->WechatClientService->inQueue($msg_str);

            // 构造消息:别人的故事, 放入队列
            $story = $this->WechatClientService->getStory($otherId_str);
            $msg_story = ["touser"=>$from, 'msgtype'=>$story['MsgType'],
                'content'=>$story['Content'], 'mediaId'=>$story['MediaId']];
            $msg_story_str = json_encode($msg_story);
            $this->LogService->in('get story, $otherId:'.$otherId. ', $otherId_str:'.$otherId_str. ', $msg_story_str:'.$msg_story_str);
            $this->WechatClientService->inQueue($msg_story_str);

            // 构造消息:联系方式,放入队列
            $msg_cnt = $this->activity_leaveContact();
            $msg_wxid = ["touser"=>$from, 'msgtype'=>'text',
                'content'=>$msg_cnt];
            $msg_wxid_str = json_encode($msg_wxid);
            $this->WechatClientService->inQueue($msg_wxid_str);

            // 记录此用户正在读哪个用户的故事
            $this->WechatClientService->setReadingWhose($from_str, $otherId);

            // 记录step
            $this->WechatClientService->setStep($from_str, 3);

            // 返回提示信息
            return $this->WechatClientService->transmitText($object,  $result);
//            return '';
        }

        // --- 接收联系方式
        if($currentStep == 3){
            // 返回的提示信息
            $result = "是否确认发送给对方？确认回复 1，重写回复 2";

            // 保存联系方式
            $this->WechatClientService->saveLeaveMsg($from_str, $txt);
            $this->WechatClientService->setLeaveMsgType($from_str, 'txt');

            // 记录step
            $this->WechatClientService->setStep($from_str, 4);

            // 返回提示信息
            return $this->WechatClientService->transmitText($object,  $result);
//            return '';
        }

        //--- 确认发送联系方式
        if($currentStep == 4 && $txt == '1'){
            // 返回的提示信息
            $result = "对方已经收到你的留言";

            // 构造消息,放入队列,发送给对方
            $otherId = $this->WechatClientService->getReadingWhose($from_str);
            $otherId_str = md5($otherId);
            $thisNick = $this->WechatClientService->getNick($from_str);
            
            // 留言类型
            $leavemsg_type = $this->WechatClientService->getLeaveMsgType($from_str);
            $leavemsg = $this->WechatClientService->getLeaveMsg($from_str);
            if(!$leavemsg_type || $leavemsg_type == 'txt'){ // 文字
                $msg = ["touser"=>$otherId, 'msgtype'=>'text',
                    'content'=>'@'.$thisNick.' 看到了你的'.$this->activityKeyWords.',给你的留言：'.$leavemsg];
                $msg_str = json_encode($msg);
                $this->WechatClientService->inQueue($msg_str);
            }
            elseif ($leavemsg_type == 'voice') { // 语音
                $msg = ["touser"=>$otherId, 'msgtype'=>'text',
                    'content'=>'@'.$thisNick.' 看到了你的'.$this->activityKeyWords.',给你的留言：'];
                $msg_str = json_encode($msg);
                $this->WechatClientService->inQueue($msg_str);
                
                $msg_voice = ["touser"=>$otherId, 'msgtype'=>'voice', 'mediaId'=>$leavemsg];
                $msg_voice_str = json_encode($msg_voice);
                $this->WechatClientService->inQueue($msg_voice_str);
            }
            

            // 构造结束信息,放入队列
            $msg_end = ["touser"=>$from, 'msgtype'=>'text',
                'content'=>$this->activity_end()];
            $msg_end_str = json_encode($msg_end);
            $this->WechatClientService->inQueue($msg_end_str);

            // 构造二维码信息，放入队列
            $mediaId_qr = $this->WechatClientService->getQrMediaId();
            $this->log('$mediaId_qr:'.$mediaId_qr);
            $data = array('touser'=>$from, 'msgtype'=>'image',
                'mediaId'=>$mediaId_qr );
            $this->WechatClientService->inQueue(json_encode($data));

            // 记录step
            $this->WechatClientService->setStep($from_str, 5);

            // 返回提示信息
            return $this->WechatClientService->transmitText($object,  $result);
//            return '';
        }

        //--- 重写联系方式
        if($currentStep == 4 && $txt == '2'){
            $otherId = $this->WechatClientService->getReadingWhose($from_str);
            $otherId_str = md5($otherId);
            $otherNick = $this->WechatClientService->getNick($otherId);
            // 返回的提示信息
//            $result = "现在，如果你想和@'.$otherNick.' 继续交流，请文字回复ta ,并且留下你的联系方式，对方会立即收到。";
//            $result = "对{$otherNick}这个爆料，你有什么要问的？请文字回复TA，并且留下你的联系方式，对方会立即收到。";
//            $result = "你对他的计划怎么看？愿意达成监督同盟吗？请文字回复TA ，留下你的联系方式，对方会立即收到。";
            $result = $this->activity_leaveContact();

            // 记录step
            $this->WechatClientService->setStep($from_str, 3);

            // 返回提示信息
            return $this->WechatClientService->transmitText($object,  $result);
//            return '';
        }


        $keyWord = trim($object->Content);
        //搜索
        $content = $this->doSearch($keyWord);


        if (isset($content[0])){
            $result = $this->WechatClientService->transmitNews($object, $content);
        }else{
            $content = '欢迎访问!';
            $result = $this->WechatClientService->transmitText($object, $content);
        }

        return $result;
    }

    // 接收语音信息
    public function receiveVoice($object){
        $pramas = (array)$object;
        $result = '';

        $from = $pramas['FromUserName'];
        $from_str = md5($from);
        $mediaId = $pramas['MediaId'];
        $type = $pramas['MsgType'];
        $createTime = $pramas['CreateTime'];

        $currentStep = $this->WechatClientService->getStep($from_str);

        // --- 接收故事
        if($currentStep == 1){
            // 返回的提示信息
//            $result = '是否确认交换这个'.$this->activityKeyWords.'？确认回复"1"，重写回复"2"';
            $result = $this->activity_exchangeConfirmInfo();

            // 保存故事
            $story = array('CreateTime'=>$createTime,
                'MsgType'=>$type,
                'MediaId'=>$mediaId);
            $this->WechatClientService->saveStory($from_str, $story);

            // 放入故事集合
            $this->WechatClientService->addStorySet($from);

            // 记录step
            $this->WechatClientService->setStep($from_str, 2);

            // 返回提示信息
            return $this->WechatClientService->transmitText($object,  $result);
        }
        // --- 接收联系方式
        if($currentStep == 3){
            // 返回的提示信息
            $result = "是否确认发送给对方？确认回复 1，重写回复 2";

            // 保存联系方式
            $this->WechatClientService->saveLeaveMsg($from_str, $mediaId);
            $this->WechatClientService->setLeaveMsgType($from_str, 'voice');

            // 记录step
            $this->WechatClientService->setStep($from_str, 4);

            // 返回提示信息
            return $this->WechatClientService->transmitText($object,  $result);
//            return '';
        }

        return $result;
    }
    // 接收图片信息
    public function receiveImage($object){
        $pramas = (array)$object;
        $result = '';

        $from = $pramas['FromUserName'];
        $from_str = md5($from);
        $mediaId = $pramas['MediaId'];
        $type = $pramas['MsgType'];
        $createTime = $pramas['CreateTime'];

        $currentStep = $this->WechatClientService->getStep($from_str);


        // --- 接收故事
        if($currentStep == 1){
            // 返回的提示信息
//            $result = '是否确认交换这个'.$this->activityKeyWords.'？确认回复"1"，重写回复"2"';
            $result = $this->activity_exchangeConfirmInfo();

            // 保存故事
            $story = array('CreateTime'=>$createTime,
                'MsgType'=>$type,
                'MediaId'=>$mediaId);
            $this->WechatClientService->saveStory($from_str, $story);

            // 放入故事集合
            $this->WechatClientService->addStorySet($from);

            // 记录step
            $this->WechatClientService->setStep($from_str, 2);

            // 返回提示信息
            return $this->WechatClientService->transmitText($object,  $result);
        }
        return $result;
    }

    

    //客服回复
    private function customSend($data){
        $accessToken = $this->WechatClientService->getAccessToken();
        if(!$accessToken) return false;

        $url = $this->WechatClientService->customSend.'?access_token='.$accessToken;
        $result = curlHttpClient($url,$data,'POST');
        return $result;


    }
    //客服消息回复模版
    private function getCustomMsgData($touser,$type,$msg)
    {
        if(empty($touser) || empty($type) || empty($msg)) return false;
        return array_merge([
            'touser' => $touser,
            'msgtype' => $type],
            $msg
        );

    }

    //处理搜索事件
    public function doSearch($keyWord){
        $result = $this->WechatSearchService->searchAnswer($keyWord);
        return $result;
    }
    //一次性订阅消息
    public function sendTemplateMsg(){


        $openid         = $this->input->get("openid");
        $template_id    = $this->input->get("template_id");
        $action         = $this->input->get("action");
        $scene         = $this->input->get("scene");
        //$reserved       = $this->input->get("reserved");
        $this->setStatus(false);
        if(!empty($openid) && $action == 'confirm'){
            //获取access_token
            $accessToken = $this->WechatClientService->getAccessToken();
            if(!$this->WechatClientService->status){
                $this->setMsg($accessToken);
                $this->outputForJson();
            }
            //获取用户信息
            $sendPrams = array(
                'touser'=>$openid,
                'template_id'=>$template_id,
                'url'=>'www.idsdayanjing.com',
                'scene'=>$scene,
                'title'=>'这是标题',
                'data'=>array(
                    'content'=>array(
                        'value'=>'这是内容，是内容，内容，容，口。',
                        'color'=>'#FF7F24',
                    )
                ),
            );
            $sendResult = curlHttpClient($this->WechatClientService->sendTemplateMsg.'?access_token='.$accessToken,json_encode($sendPrams),'POST');
            $result = json_decode($sendResult,true);
            if($result['errcode']==0){
                $this->setStatus(true);
                $this->setMsg('发送成功！');
                $this->outputForJson($result);
            }else{
                $this->setMsg('发送失败！');
                $this->outputForJson($result);
            }


        }else{
            $this->setMsg('拒绝授权！');
            $this->outputForJson($this->input->get());
        }


    }
    function uploadimg(){
        header('Content-type:text/html; charset=utf-8');  //声明编码
        $token = $this->WechatClientService->getAccessToken();
        var_dump($token);
        $ch = curl_init();
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$token.'&type=image';
        $curlPost = array('media'=>'@/home/mapp/nginx/html/snob_php/a.jpeg');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1); //POST提交
        curl_setopt($ch, CURLOPT_POSTFIELDS,$curlPost);
        $data =curl_exec($ch);
        curl_close($ch);
        echo '<pre>';
        var_dump($data);
    }
    public function materialUpload(){
        $type  = $this->input->get("type");
        $name  = $this->input->get("name");
        $result = $this->WechatClientService->materialUpload($type,$name);
        echo $result;
        exit;
    }

    public function mediaUpload(){
        $type  = $this->input->get("type");
        $name  = $this->input->get("name");
        $result = $this->WechatClientService->mediaUpload($type,$name);
        echo $result;
        exit;
    }

    public function createMenu(){
        $result = $this->WechatClientService->createMenu();
        echo $result;
        exit();
    }

    public function delMenu(){
        $result = $this->WechatClientService->delMenu();
        echo $result;
        exit();
    }

    public function saveMedia(){
        $media = $this->input->get("media");
        $type  = $this->input->get("type");
        $name  = $this->input->get("name");
        $result = $this->WechatClientService->saveMedia($media,$type,$name);
        echo $result?'sucess':'failed';
        exit;
    }
    
    // --------------------- start 交换活动的动态信息，每次新活动时需要替换
    
    /**
     * 活动关键字
     * @return string
     */
    public function activity_keywords(){
        return '晚安';
    }
    
    /**
     * 留言提示信息
     * @return string
     */
    public function activity_leaveContact(){
        return "听了ta的晚安你有什么想法？【说出】你的感受。\n\n你也可以留下联系方式，继续交流哦~";
    }
    
    public function activity_end(){
        return '交换活动结束。邀请更多人参加睡前晚安，请把下面的二维码发到朋友圈吧。\n\n'.
                '（想听更多的睡前故事，可再次输入“晚安”）';
    }
    
    /**
     * 活动提示信息
     * @param type $nick
     * @return type
     */
    public function activity_tipInfo($nick){
        return "还没睡，睡不着的小伙伴，欢迎来参加【用声音和你说晚安】活动\n\n".
            "现在，【用语音】说出：你现在在哪儿？为什么还不睡？心情怎么样？.....\n\n".
            "你还可以“哼唱”一小段歌，或者“读”一段你手边的书，和陌生人说晚安~";
    }
    
    /**
     * 确认交换的提示信息
     * @return type
     */
    public function activity_exchangeConfirmInfo(){
        return "开始交换，回复1；\n重新录制，回复2；";
    }
    
    
    // --------------------- end
}