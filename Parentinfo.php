<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\api\controller\Basics;
use think\Session;

class Parentinfo extends Basics
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
       |++ Interested friends can add me QQ:**769*3598   ---->Cracking a digit   ----->You'll get me.                 ++|
       |++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++|
       |Tip: God of procedure+______^_^________^_^________^_^________^_^________^_^________^_^_________^_^_____+(joke)  |
       |————————————————————————————————————————————————————————————————————————————————————————————————————————————————|
    */
    /**
     * garden_spare  controller
     *
     * @return \think\Response
     */

     /*
           我的  
     */
     // 根据家长电话号 为登录账号  密码后台随机先生成一个    

    public function getParentInfo(Request $req)
    {
        //获取家长信息
              $ctoken = $this->getReq();
              if(!$ctoken){
                   $msg = 'token失效 请重新登录';
                   rData('300','失败',$msg);
              }
              $id =$ctoken['id'];
              $where = "id = $id";
              $ParentInfo = Db::name('student_family')->where($where)->field('id,name,tel')->find();
              if(!empty($ParentInfo)){
                //$this->assign('data',$data);
                // return $this->fetch('./application/api/view/parent/parentsMy.html');
                   rData('200','成功',$ParentInfo);
              }else{
                  $msg = '无数据';
                  rData('200','成功',$msg);
                // return redirect('去登录  没有登录页');
            }
    }


       //家长登录后自己修改密码
    public function updateParentPwd(Request $req)
    {
         // 获取家长ID   修改密码有问题
        $ctoken = $this->getReq();
        if(!$ctoken){
            $msg = 'token失效 请重新登录';
            rData('300','失败',$msg);
        }
        $req = $req->only('password,rpassword');
        $password = $req['password'];
        $repassword = $req['rpassword'];
        if(!($password===$repassword)){
             $msg = '两次密码不一致';
             rData('200','失败',$msg);die;
        }
        $id = $ctoken['id'];
        $where = "id =$id";
        $data['password'] =md5($password);
        $res = Db::name('student_family')->where($where)->update($data);
        $sql = Db::name('student_family')->getlastsql();
        if($res){
             $msg = '修改密码成功';
             rData('200','成功',$msg);
        }else{
             $msg = '修改密码失败';
             rData('300','失败',$msg);
        }

    }


    // 缴费详情页
    public function PayPage()
    {
          $ctoken = $this->getReq();
          if(!$ctoken){
               $msg = 'token失效 请重新登录';
               rData('300','失败',$msg);
          }
         $payInfo = $this->PaymentRecord($ctoken['id']);
         if($payInfo){
                rData('200','成功',$payInfo);
                // $this->assign('payInfo',$payInfo);
                // return $this->fetch('./application/api/view/parent/parentsPay.html');
         }else{
                $msg = '没有该学生缴费记录';
                return $msg;
            //rData('1','没有缴费记录',$msg) ;
         }
    }

   
    //获取学生信息   
    private function getStudentInfo($familyid='')
    {
        $id = $familyid;
        $where = "family_id = $id"; 
        $StudentInfo = Db::name('student')->where($where)->field('id,name,class_id,garden_id')->find();
        if($StudentInfo){
             return $StudentInfo;
        }else{
             $msg = '没有该学生记录';
             return $msg;
        }
    }


   
   // 获取缴费记录
    private  function PaymentRecord($tokenid='')
    {
       
        $id = $tokenid;
        $student = $this->getStudentInfo($id);
        $class_id = $student['class_id'];
        $garden_id = $student['garden_id'];
        //此处多个 and 条件  是为了更精准   如果后者觉得没有必要 可以酌情减去
        $where ="student_id = {$id}  and class_id = {$class_id} and state= 1 and garden_id ={$garden_id}";  
        $payInfo = Db::name('cause a')
                      ->join('money b','b.cause_id = a.id')
                      ->where($where)->field('a.name as causename,b.money')->select();
        //此处判断缴费数据是二维还是一维  防止该学生只有一条缴费记录
         // $sql = Db::name('cause')->getlastsql();
        if(isset($payInfo['causename'])){
            $payInfo['causename'] = '应收'.$payInfo['causename'];
            $payInfo['sum'] =$payInfo['money'];
        }else{
            foreach($payInfo as $k=>$v){
                $tmp = '应收'.$v['causename'];
                $payInfo[$k]['causename'] = $tmp;
            }
             $arr = array_column($payInfo,'money');
             $sum = array_sum($arr);
             $payInfo['sum'] = $sum;
        }  
         return $payInfo;
    }



     //没有登录 忘记密码
     public function ForgetPassword(Request $req)
    {
          

    }

    /**
     * .
     *
     * @return \think\Response
     */
    /*
        
    */
   //前端展示获取所有的信息  估计是目前用不到  
    protected  function show()
    {
        $data= $this->getParentInfo();
        if(!gettype($data) == 'string'){
            $student =$this->getStudentInfo($data['id']);
            if(gettype($student) == 'string'){
                rData('0','失败',$student);die;
            }
            $money = $this->PaymentRecord($student['id']);
            if(gettype($money) == 'string'){
                rData('0','失败',$money);die;
            }
            $data['student'] = $student;
            $data['paymentrecord'] = $money;
            rData('1','成功',$data);
        }else{
            return $ParentInfo;die;
        }

    }
}
