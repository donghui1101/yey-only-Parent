<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\api\controller\Basics;
use think\Session;

class Gardenhome extends Basics
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
       |++ Interested friends can add me QQ:11769*3598   ---->Cracking a digit   ----->You'll get me.                 ++|
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
          家园共育 家长端
    */
   //家长观看
    public function index()
    { 

        //获取家园共育的信息   根据班级
        $family = session::get('usersid');
        if(!empty($family)){
              $id = $family['id'];
              $whereOne = "family_id = $id";
              $student = Db::name('student')->where($whereOne)->field('id as studentid,class_id')->find();
              $class_id = $student['class_id'];
              $now = date("Y-m-d",strtotime("now"));
              $start = date("Y-01-01",strtotime("now"));
              $where = "class_id ={$class_id} and (addtime between '{$start}'and '{$now}')";
              $data = Db::name('garden_home')->where($where)->select();
              if($data){
                   $msg = '获取数据成功';
                  // $this->view('parent/parentsEducation',$data);
                  rData('1','成功',$data);
              }else{
                   $msg = '无数据';
                   rData('0','失败',$msg);
              }
        }else{
            return redirect('去登录  没有登录页');
        }      

    }
      return $this->fetch('./application/api/view/parent/parentsEducation.html');
   
}
