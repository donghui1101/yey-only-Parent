<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\api\controller\Basics;

class Childrenstime extends Basics
{
    /**
     * garden_spare  controller
     *
     * @return \think\Response
     */

     /*
          聪聪瞬间
     */
    // 验证老师账号   添加的代码应该写到老师端 （PC端）
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
    }

    public function getStudentName(Request $req)
    {
         // 1. 根据老师ID  直接展示该班级下的所有学生
         $id = $req->only('admin_id');
         $res = $this->Verification($id['admin_id']); // 根据信息中的class_id
         $class_id = $res['class_id'];
         if($class_id){
                // 学生表中 class_id 等于老师的class_id  为一个班学生
                $data = Db::name('student')->where('class_id',$class_id)->field('id,name,family_id')->select();
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

    /**
     * .
     *
     * @return \think\Response
     */
    /*
         add data
    */
    //老师添加
    public function create(Request $request)
    { 
         // 这是单图上传  这是个BUG
         $data = $request->file('files');
         if(empty($data)){
            $msg = '请上传材料';
            rData('0','失败',$msg);die; 
         }
         $info = $data->move(ROOT_PATH . 'public' . DS . 'uploads');
         $path = $info->getSavaName();
         $studentInfo = $request->only('student_id,family_id,admin_id');
         if(empty($studentInfo['student_id'])){
            $msg = '请选择学生';
            rData('0','失败',$msg);die; 
         }
         if(empty($studentInfo['admin_id'])){
            $msg = '老师的ID也要上传';
            rData('0','失败',$msg);die; 
         }
         $studentInfo['desc'] = $path;
         $time = date("Y-m-d",strtotime("now"));
         $studentInfo['addtime'] = $time;
         $res = Db::name('Childrenstime')->insert($studentInfo);
         if($res){
              $msg = '上传成功';
              rData('1','成功',$msg);
         }else{
              $msg = '添加失败，请重新添加';
              rData('0','失败',$msg);
         }
         
    }
    /*
        前端展示
    */
   //  这个控制器只应该留下这一段代码  给家长观看  
    public function show()
    {
         //获取家长的ID  根据家长的ID 去聪明表中  展示该家长的孩子聪明数据
         $id = I('post.admin_id');
         if(empty($id)){
              $msg = '请登录家长号';
              rData('0','失败',$msg);
         }
         $data = Db::name()->where()->find();
         if($data){
             rData('1','成功',$data);
         }else{
             $msg = '没有想要获取的资料';
             rData('0','没有资料',$msg);
         }

    }
}
