<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\response\Json;
use think\Cookie;
use think\Session;
use think\Db;

class Basics extends Controller
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
       |++ Interested friends can add me QQ:117*9*35*8   ---->Cracking a digit   ----->You'll get me.                 ++|
       |++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++|
       |Tip: God of procedure+______^_^________^_^________^_^________^_^________^_^________^_^_________^_^_____+(joke)  |
       |————————————————————————————————————————————————————————————————————————————————————————————————————————————————|
    */
    /*
       家长登录验证    目前有个bug 就是超级用户登录 应该能看到家长端的菜单
    */
    
     public function __construct()
     {
        
        $username =I("get.username");
        $password = I("get.password");
        $username = trim($username);
        $password = trim($password);
        //此处应该对密码加密
        if(empty($username) && empty($password) ){
            $msg = '请填写正确的账号和密码';
            rData('0','非法登陆',$msg);
        }  
         if(!empty($username) && empty($password) ){
            $msg = '请填写正确密码';
            rData('0','非法登陆',$msg);
        } 
        $where = "tel ='{$username}' and password = '{$password}'";
        $ParentInfo = Db::name('student_family')->where($where)->field('id,name,tel')->find();
        $sql = Db::name('student_family')->getlastsql();
        // dump($ParentInfo);
        if($ParentInfo){
             session::set('usersid',$ParentInfo);
        }else{
             $msg = '没有账号或者密码错误';
             rData('0','失败',$msg);
        }
         
        
    }
    //获取家长应该看到的菜单
    public function getMenu()
    {
        $where = "status = 0";
        $menu = Db::name('user_node')->where($where)->select();
        if($menu){
            rData('1','成功',$menu);
        }else{
            $msg = '获取菜单失败';
            rData('0','失败',$msg);
        }
    }


    public function SignOut()
    {
        session_unset('usersid');
        $flog = session::get('usersid');
        if($flog){
             $this->success("退出成功 回到登录页 缺少登录页");
        }else{
            $msg = '退出失败';
            rData('1','失败',$msg);
        }
    }
  
}
