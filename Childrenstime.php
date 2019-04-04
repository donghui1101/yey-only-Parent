<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\api\controller\Basics;
use think\Session;

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
    public function show()
    {
         //获取家长的ID  根据家长的ID 去聪明表中  展示该家长的孩子聪明数据
         $parent = session::get('usersid');
         $id = $parent['id'];
         if(empty($id)){
             return redirect('去登录  没有登录页');
         }
         $whereOne = "family_id = $id";
         $student = Db::name('student')->where($whereOne)->field('id as student_id,name as student_name')->find();
         if(empty($student)){
             $msg = '无该学生数据';
             rData('0','失败',$msg);
         }
         $studentid = $student['student_id'];
         $where = "student_id = $studentid";
         $data = Db::name('childrenstime')->where($where)->find();
         if($data){
             rData('1','成功',$data);
         }else{
             $msg = '没有想要获取的资料';
             rData('0','没有资料',$msg);
         }
         return $this->fetch('./application/api/view/parent/parentsSmart.html')

    }
}
