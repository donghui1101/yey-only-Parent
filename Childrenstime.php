<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\api\controller\Basics;
use think\Session;

// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:*');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
class Childrenstime extends Basics
{
      /*
        ________________________________________________________________________________________________________________
       |++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++|
       |++  author: Great programmer Mr. Ma                                                                           ++|
       |++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++|
       |++  date:2019-04-03                                                                                             |
       |++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++|
       |++  brief introduction:                                                                                         |
       |________________________________________________________________________________________________________________|
       |++ Interested friends can add me QQ:1*769*35*8   ---->Cracking a digit   ----->You'll get me.                 ++|
       |++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++|
       |Tip: God of procedure+______^_^________^_^________^_^________^_^________^_^________^_^_________^_^_____+(joke)  |
       |————————————————————————————————————————————————————————————————————————————————————————————————————————————————|
    */
    /**
     * 
     *
     * @return \think\Response
     */
     /*
        聪聪瞬间   家长端展示  
     */
    public function index()
    {
        $ctoken = $this->getReq();
        if(!$ctoken){
            $msg = 'token失效 请重新登录';
            rData('300','失败',$msg);
        }
        $data = $this->getData($ctoken['id']);
        if($data){
             rData('200','成功',$data);
             //$this->assign('data',$data);
            // return $this->fetch('./application/api/view/parent/parentsSmart.html');
        }else{
             $msg = '没有想要获取的资料';
             rData('200','没有资料',$msg);
        }
    }
   
}
