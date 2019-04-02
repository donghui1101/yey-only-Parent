<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\api\controller\Basics;

class Parentinfo extends Basics
{
    /**
     * garden_spare  controller
     *
     * @return \think\Response
     */

     /*
           我的  目前这段代码好像写到这里不合适
     */
     // 根据家长电话号 为登录账号  密码后台随机先生成一个    

    public function getParentInfo(Request $req)
    {
        //获取家长信息
        $admin_id =I("post.admin_id");
        $password = I("post.password");
        $username = trim($username);
        $pwd = trim($pwd);
        if(empty($admin_id) && empty($password) ){
            rData('0',"非法登陆");
        }  
        //从session 中也可以取出家长的ID  然后去家长表中获取信息
        $where = "id = $id";
        $ParentInfo = Db::name('student_family')->where($where)->field('id,name,tel')->find()
        if($ParentInfo){
             return $Parentinfo;
        }else{
             $msg = '没有这个账号或者没有添加电话';
             return $msg
        }
    }
       //家长自己修改密码
    public function updateParentPwd(Request $req)
    {
         // 获取家长ID
        $data = $req->only('password');
        $where = "id = $家长ID";
        $res = Db::name('student_family')->where($where)->updata($data);
        if($res){
             $msg = '修改密码成功';
             rData('1','成功',$msg);
        }else{
             $msg = '修改密码失败';
             rData('1','失败',$msg);
        }

    }
    //获取学生信息   
    private function getStudentInfo($familyid='')
    {
       //根据家长ID 去寻找关联的学生
        $id = $familyid;
        $where = "family_id = $id";
        $StudentInfo = Db::name('student')->where($where)->field('id,name')->find();
        if($StudentInfo){
             return $StudentInfo
        }else{
             $msg = '没有该学生记录';
             return $msg;
        }
    }
   
   // 获取缴费记录
    private  function PaymentRecord($studentid='')
    {
        $id = $studentid;
        $where ="student_id = $id";  
        $payInfo = Db::name('money')->where($where)->field('交的啥  交了多少钱')->find();
        if($payInfo){
            return $payInfo;
        }else{
            $msg = '没有该学生缴费记录';
            return $msg;
        }
      
    }

    public function PayRecord()
    {
        $rows = $this->getParentInfo();
        if(gettype($rows) == 'string'){
             rData('1','失败',$rows);die;
        }    
        $student =$this->getStudentInfo($rows['id']);
        if(gettype($student) == 'string'){
             rData('1','成功',$student);die;
        }
        $money = $this->PaymentRecord($student['id']);
        if(gettype($money) == 'string'){
             rData('1','成功',$money);die;
        }
        $res = $money;
        if($res){
            rData('0','成功',$res);
        }

    }
    /**
     * .
     *
     * @return \think\Response
     */
    /*
         add data
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
