<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\api\controller\Base;

class Teacherchildt extends Base
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
       |++ Interested friends can add me QQ:11769*3**8   ---->Cracking a digit   ----->You'll get me.                 ++|
       |++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++|
       |Tip: God of procedure+______^_^________^_^________^_^________^_^________^_^________^_^_________^_^_____+(joke)  |
       |————————————————————————————————————————————————————————————————————————————————————————————————————————————————|
    */
     /*
          聪明瞬间 老师端
     */
    // // 验证老师账号     
    private function Verification($id='')
    {
          //根据传来的ID 获取该老师的信息   没有的话 传回false  有的话 传回class_id 
         $where = "staff_id = $id";
         $data = Db::name('staff')->where($where)->field('class_id')->find();
         if($data){
              return $data;
         }else{
              $msg =false;
              return  $msg;
         }
    // }
    // 获取学生名字
     public function getStudentName(Request $req)
    {
         // 1. 根据老师ID  直接展示该班级下的所有学生
         $id = $req->only('admin_id');
         $res = $this->Verification($id['admin_id']); // 根据信息中的class_id
         if(!$res){
             $msg = '没有您的数据';
             rData('0','失败',$msg);
         }
         $class_id = $res['class_id'];
         if($class_id){
                // 学生表中 class_id 等于老师的class_id  为一个班学生
                $data = Db::name('student')->where('class_id',$class_id)->field('id,name')->select();
                if(!empty($data)){
                        dump($data);die;
                        rData('1','成功',$data);
                }else{
                        $msg = '数据库有错误';
                        rData('0','失败',$msg);
                }
         }else{
              $msg = '您不是带班老师，无法获取该班级学生信息';
              rData('0','失败',$msg);
         }
  
    }
  
      public function create(Request $request)
    { 
         // 这是单图上传  这是个BUG
         $files = $request->file('files');
         if(empty($files)){
            $msg = '请上传材料';
            rData('0','失败',$msg);die; 
         }
         $info = $files->move(ROOT_PATH . 'public' . DS . 'uploads');
         $path = $info->getSavaName();
         $studentInfo = $request->only('student_id,admin_id');
         if(empty($studentInfo['student_id'])){
            $msg = '请选择学生';
            rData('0','失败',$msg);die; 
         }
         if(empty($studentInfo['admin_id'])){
            $msg = '老师的ID也要上传';
            rData('0','失败',$msg);die; 
         }
         $studentInfo['desc'] = $path;
         $studentInfo['staff_id'] = $studentInfo['admin_id'];
         $time = date("Y-m-d",strtotime("now"));
         $studentInfo['addtime'] = $time;
         $res = Db::name('childrenstime')->insert($studentInfo);
         if($res){
              $msg = '上传成功';
              rData('1','成功',$msg);
         }else{
              $msg = '添加失败，请重新添加';
              rData('0','失败',$msg);
         }
         
    }

    public function  add()
    {
      //多图上传
         $files = $req->file('images');
         $studentInfo = $request->only('student_id,admin_id');
         $path = [];
         foreach($files as $k=>$v){
              if(!empty($v)){
                   $info = $files->move(ROOT_PATH . 'public' . DS . 'uploads');
                   $temp = $info->getSavaName();
                   $path[] = array_push($temp);
              }
         }
         $path = implode('@',$path);
       //视频上传
         
         $studentInfo['desc'] = $path;
         $studentInfo['staff_id'] = $studentInfo['admin_id'];
         if($studentInfo){
               $res = Db::name('childrenstime')->insert($studentInfo);
               if($res){
                    $msg = '添加成功';
                    rData('1','成功',$msg);
               }else{
                    $msg = '上传资料失败';
                    rData('0','失败',$msg);
               }
         }else{
              $msg = '请填写完整资料';
              rData('0','失败',$msg);
         }

    }

   
}
