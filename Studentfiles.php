<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\api\controller\Basics;
use think\Session;

class Studentfiles extends Basics
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
       |++ Interested friends can add me QQ:1*769*3*98   ---->Cracking a digit   ----->You'll get me.                 ++|
       |++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++|
       |Tip: God of procedure+______^_^________^_^________^_^________^_^________^_^________^_^_________^_^_____+(joke)  |
       |————————————————————————————————————————————————————————————————————————————————————————————————————————————————|
    */
    /*
       家长登录首页
    */
     //获取学生档案  
    public function studentFile()
    {
        $ctoken = $this->getReq();
        if(!$ctoken){
            $msg = 'token失效 请重新登录';
            rData('300','失败',$msg);
        }
        $id = $ctoken['id'];
        if(!empty($id)){
               $studentInfo = $this->getStudentInfo($id);
               if(!$studentInfo){
                   $msg = '没有该学生记录';
                   rData('200','成功',$msg);
               }
               //获取来源信息
               if(!empty($studentInfo['source_id'])){
                     $source_id = $studentInfo['source_id'];
                     $where="id = $source_id";
                     $msg = Db::name('message_source')->where($where)->find();
                     $studentInfo['msg'] = $msg;
                     $studentInfo['teacher'] = $this->getTeacher($studentInfo['class_id']);
                     //$this->assign('studentinfo',$studentInfo);
                     //return $this->fetch('./application/api/view/parent/pChildDetails.html');
                    rData('200','成功',$studentInfo); 
               }
        }else{
               $msg = '登录超时';
               rData('300','登录超时',$msg); 
        }
       
      
        
    }


    //获取老师信息
    private function getTeacher($classid='')
    {
        $cid = $classid;
        //  重置数据表后  职位ID变化 如果职位名称也会变化  请修改以下职位名称 即可
        $position = '主班';
        $whereO = " name Like '%$position%' ";
        $position_id =Db::name('organization')->where($whereO)->field('id')->find();
        $sql = Db::name('organization')->getlastsql();
        if(!$position_id){
             return '无负责老师';
        }
        $oid = $position_id['id'];
        $where=" class_id = $cid  and  o_id = $oid";
        $teacher = Db::name('staff')->where('class_id',$cid)->field('staff_id,staff_name')->find();
        if($teacher){
            return $teacher;
        }else{
            $msg = '获取老师信息失败';
            return $msg;
        }
    }


     //获取学生信息   
    private function getStudentInfo($familyid='')
    {
        $id = $familyid;
        $where = "family_id = $id";
        $StudentInfo = Db::name('student')->where($where)->field('name,sex,place,birthday,home,garden_id,source_id,class_id,once_garden')->find();
        if($StudentInfo){
             return $StudentInfo;
        }else{
             $msg = false;
             return $msg;
        }
    }
    
    //获取最新通知消息
    public function getNewMsg()
    {
        $family = session::get('usersid');
        if(!$family){
            
            //return redirect('去登录  没有登录页');
        }
        $familyid = $family['id'];
        $student = $this->getStudentInfo($familyid);
        if(!$student){
            $msg = '没有该学生记录';
            rData('200','成功',$msg);
        }
        $garden_id = $student['garden_id'];
        if(!$garden_id){
            $msg = '该学生信息有错误请更正';
            rData('200','成功',$msg);
        }
        $where ="inform_status =1 and garden_id = {$garden_id} ";
        $data = Db::name('inform')->where($where)->field('addtime,inform_title,inform_desc,inform_photo')->order('id','DESC')->limit(1);
        if($data){
            rData('200','成功',$data);
        }else{
            $msg = '数据库有问题';
            rData('500','成功',$msg);
        }
    }
    //获取最近通知消息
    public function getMsg()
    {
         //获取更多消息  展示当月
        $time = date("Y-m-d" ,strtotime("now"));
        $month = date("Y-m-00" ,strtotime("now"));
        $where = "addtime <= {$time} and addtime >= {$month}";
        $data = Db::name('消息表')->where($where)->field('添加时间，通知的消息')->select();
    }

   

    //获取膳食
    public function food()
    {   
          //应该是只获取当前家长孩子所在园区的营养膳食
         $food = Db::name('食物表')->select(); 
         return $this->fetch('./application/api/view/parent/phb5.html');
    }
  
}
