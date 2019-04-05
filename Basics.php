<?php

namespace app\api\controller;

use app\admin\logic\UpgradeLogic;
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
    */
    
     public function __construct()
     {
        session_start();
        header("Cache-control: private");  // history.back返回后输入框值丢失问题 参考文章 http://www.tp-shop.cn/article_id_1465.html  http://blog.csdn.net/qinchaoguang123456/article/details/29852881
        parent::__construct();
        $upgradeLogic = new UpgradeLogic();
        $upgradeMsg = $upgradeLogic->checkVersion(); //升级包消息        
        $this->assign('upgradeMsg',$upgradeMsg);    
        //用户中心面包屑导航
        $navigate_admin = navigate_admin();
        $this->assign('navigate_admin',$navigate_admin);
        tpversion();     
        
     }

    public function signIn(Request $req)
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
             $_SESSION['flog'] = true;
             $_SESSION['name'] = $ParentInfo['name'];
             $_SESSION['id'] = $ParentInfo['id'];
              if($_SESSION['flog']){
                   $menu = $this->getMenu();
                   $this->assign('menu',$menu);
                   return $this->fetch('./application/api/view/parent/index.html');
              }else{
                    return $this->redirect('登录页');
              }
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
            return $menu;
        }else{
            $msg = '获取菜单失败';
            return $msg;
        }
    }

   // 退出
    public function signOut()
    {
        $flog = $_SESSION['flog'] = false;
        if($flog){
            $msg = '退出失败';
            rData('1','失败',$msg);
        }else{
             unset( $_SESSION['name']);
             unset( $_SESSION['id']);
             $this->success("退出成功     回到登录页 缺少登录页");
        }
    }

  // 返回主页面
    public function goBack()
    {
         $this->fetch('./application/api/view/parent/index.html');
    }
  
}
