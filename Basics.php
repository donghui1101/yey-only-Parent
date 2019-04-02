<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\response\Json;
use think\Cookie;
use think\Session;

class Basics extends Controller
{
    /**
     * garden_spare  controller
     *
     * @return \think\Response
     */
    /*
       家长登录验证
    */
     public function __construct()
     {
        $admin_id =I("post.admin_id");
        $password = I("post.password");
        $username = trim($username);
        $pwd = trim($pwd);
        if(empty($admin_id) && empty($password) ){
            $msg = '请填写正确的账号和密码';
            rData('0','非法登陆',$msg);
        }  
        $where = "tel ={$username} and password = {$pwd}";
        $ParentInfo = Db::name('student_family')->where($where)->field('id')->find()；
        if($ParentInfo){
             return $Parentinfo;
        }else{
             $msg = '没有账号或者密码错误';
             return $msg
        }
         
        
    }
    //获取家长应该看到的菜单
    public function getMenu()
    {

    }
  
}
