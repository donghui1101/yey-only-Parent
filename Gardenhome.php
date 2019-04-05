<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\api\controller\Basics;
use think\Session;

class  Gardenhome  extends Basics
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
    public function show()
    {
         $data = $this->getData();
          if($data){
               if(isset($data['addtime'])){
                    $data['week'] = $this->get_week($data['addtime']); 
               }else{
                   foreach($data as $k=>$v){
                       $data[$k]['week'] = $this->get_week($v['addtime']);
                   }
               }
               $this->assign('data',$data);
               return $this->fetch('./application/api/view/parent/parentsEducation.html');
          }else{
               $msg = '无数据';
               return $msg;
          }
    }
   
   //课程详情   好像没用  *__*  
   public function details()
   {
        $data = $this->getData();
        if($data){
            $this->assign('data',$data);
            return $this->fetch('./application/api/view/parent/jxxq.html');
        }else{
            return $this->redirect('重登录去');
        }
        

   }

   private  function getData()
    { 
        //获取家园共育的信息   根据班级
        $flog = $_SESSION['flog'];
        if(!$flog){
            return redirect('去登录  没有登录页');
        }
        $familyid = $_SESSION['id'];
        if(!empty($familyid)){
              $whereOne = "family_id = $familyid";
              $student = Db::name('student')->where($whereOne)->field('class_id')->find();
              $class_id = $student['class_id'];
              $now = date("Y-m-d",strtotime("now"));
              $start = date("Y-01-01",strtotime("now"));
              $where = "class_id ={$class_id} and (addtime between '{$start}'and '{$now}')";
              $data = Db::name('garden_home')->where($where)->select();
              return $data;
             
        }else{
            return redirect('去登录  没有登录页');
        }      

    }

   // 获取星期     
     private  function  get_week($date)
     {
        $date_str=date('Y-m-d',strtotime($date));
        $arr=explode("-", $date_str);
        $year=$arr[0];
        $month=sprintf('%02d',$arr[1]);
        $day=sprintf('%02d',$arr[2]);
        $hour = $minute = $second = 0;   
        $strap = mktime($hour,$minute,$second,$month,$day,$year);
        $number_wk=date("w",$strap);
        //自定义星期数组
        $weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
        //获取数字对应的星期
        return $weekArr[$number_wk];
    }
}
