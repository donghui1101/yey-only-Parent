<?php

namespace app\api\controller;

use app\admin\logic\UpgradeLogic;
use think\Controller;
use think\Request;
use think\response\Json;
use think\Cookie;
use think\Session;
use think\Db;


// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:*');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
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
        // dump($ParentInfo);
        if($ParentInfo['id']){
                   $childrenstime['token'] = $this->round().'%=a1314b@'.base64_encode($ParentInfo['id']).'%z2581314@q'.time();
                   $token = $childrenstime['token'];
                   if($token){
                       $rows['token'] =$token;
                       $rows['family_id'] = $ParentInfo['id'];
                       $rows['addtime'] = time();
                       $res = Db::name('token')->insert($rows);  //存入token
                   }
                   $childrenstime['data'] = $this->getData($ParentInfo['id']);  //首页的聪明瞬间应该是有条数的限制  看全部的去聪明瞬间详情去看
                   rData('200','成功',$childrenstime);

                   //$menu = $this->getMenu();
                   //$this->assign('menu',$menu);
        }else{
             $msg = '没有账号或者密码错误';
             rData('200','成功',$msg);
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
        $ctoken = $this->getReq();
        if($ctoken){
            $restoken = $ctoken['token'];
            $familyid = $ctoken['id'];
            $where =" family_id = {$familyid} and token = '$restoken'";
            $res = Db::name('token')->where($where)->delete();
            if($res){
                rData('200','退出成功',$res);
            }else{
                $msg = '清除token失败';
                rData('500','退出失败',$msg);
            }
        }else{
              $msg = '已经退出了，请登录';
              rData('300','有问题呦',$msg);
        }
    }

    //聪明瞬间
    protected  function getData($id='')
    {
         //获取家长的ID  根据家长的ID 去聪明表中  展示该家长的孩子聪明数据
         if(empty($id)){
             $msg = '重新登录';
             rData('300','登录超时',$msg);
         }
         $whereOne = "family_id = $id";
         $student = Db::name('student')->where($whereOne)->field('id as student_id,name as student_name')->find();
         if(empty($student)){
             $msg = '无该学生数据';
             rData('200','失败',$msg);
         }
         $studentid = $student['student_id'];
         $where = "student_id = $studentid";
         $data = Db::name('childrenstime')->where($where)->select();
         $studentname = $student['student_name'];
         $this->assign('studentname',$studentname);
         return $data;
    }

    // 解密 
    protected function checkToken($token='')
    {
        //1314 38026ed22fc1a91d92b5d2ef93540f20 258 1554520696
        //a1314bMw==25813141554531653
        $str = 'd5u%=a1314b@Mw==%z2581314@q1554547496';
        $strt ='nr%=a1314b@Mw==%z2581314@q1554547566';
        if(strlen($token)==strlen($str) ||  strlen($token)== strlen($strt)){
            $res = [];
            $preg = '/%z2581314@q/';
            $arr =  preg_split($preg,$token);
            if(count($arr)!==2){
                 $msg = '非法登录中';
                 rData('500','失败',$msg);
            }
            $time = $arr[1];
            $ntime = time();
            $pregO = '/%=a1314b@/';
            $array = preg_split($pregO, $arr[0]);
            if(count($array) !== 2){
                $msg = '您在非法登录';
                rData('500','失败',$msg);
            }
            $res['id'] = base64_decode($array[1]);
            $res['token'] = $token;
            $status =($ntime -$time)/60/60/24;
            $rows = intval($status);
            if( $rows >= 30){
                $where = "family_id = $familyid";
                Db::name('token')->where($where)->delete();
                $msg = false;
                return $msg;
            } 
             $familyid = $res['id'];

             $where ="family_id = {$familyid} and token = '$token'";
             $dataToken = Db::name('token')->where($where)->find();
             if($dataToken){
                    return $res;
             }else{
                    return false;
             }   
        }else{
            $msg = 'token 丢失数值，请重新登录';
            rData('500','失败',$msg);
        }
    }
  
    // 用在加密前缀
    private function round()
    {
          $arr = ['4t2','cgh','c3t','d5u','eg','wqe','fui','gf','fgh','hf','4t6','it','765','jn','iex','el','ks','lm','cde','mc','nr','wq','9v0'];
          $one = array_rand($arr,1);
          $res = $arr[$one];
          return $res;
    }

    protected function getReq()
    {
         $token = I('get.token');
          if(empty($token)){
                $msg = 'token 为空';
                rData('300','失败',$msg);
          }
          $ctoken =$this->checkToken($token);
          if(!$ctoken){
               $msg = 'token失效 请重新登录';
               rData('300','失败',$msg);
          }
          return $ctoken;
    }
}
