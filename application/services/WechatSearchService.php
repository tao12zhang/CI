<?php
/**微信公众号搜索内容
 * Created by PhpStorm.
 * User: XFour
 * Date: 17/7/19
 * Time: 下午12:37
 */

class WechatSearchService extends MY_Service {
    function __construct()
    {
        parent::__construct();

        $this->load->model('SearchModel');
        $this->defualtImage = '';
        $this->defualtImageL = $this->config->item('logoL');
        $this->defualtImageS = $this->config->item('logoS');
        $this->AnswerUrl = $this->config->item('answerUrl');
    }
    //联合搜索





    //搜索答案
    public function searchAnswer($keyWord){
        if(empty($keyWord)){
            return [];
        }
        $answerList = $this->SearchModel->searchAnswer($keyWord);
        //print_r($answerList);
        if(!$answerList || $answerList['code'] != 1001){
            return [];
        }
        $answerList = $answerList['data']['list'];
        //$answerList = array_slice($answerList['data']['list'],0,8);
        $i = 0;
        $list = [];
        foreach($answerList as $answer){
            //var_dump($answer);die();
            if(!empty($answer['image'])){
                $imageArr = explode(',',$answer['image']);
                $imageUrl = $imageArr[0];
            }else{
                $imageUrl = $i==0?$this->defualtImageL:$this->defualtImageS;
            }

            $list[] = array(
                'Title' => $answer['answerQuestion']['title']?strip_tags($answer['answerQuestion']['title']):'标题',
                //'Description' => $answer['content']?strip_tags($answer['content']):'描述',
                'Description' => '描述',
                'PicUrl' => $imageUrl,
                'Url' => $this->setAnswerUrl($answer['answerId']),

            );
            unset($imageUrl);
            $i++;
        }
        return $list;

    }

    private function setAnswerUrl($answerId){

        return $this->AnswerUrl."?answerId=".$answerId;

    }



    //搜索心得




}