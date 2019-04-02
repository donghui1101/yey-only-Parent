<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\api\controller\Basics;

class Homepage extends Basics
{
    /**
     * garden_spare  controller
     *
     * @return \think\Response
     */
    /*
       家长登录首页
    */

     //获取学生档案  
    public function studentFile()
    {
         //调用parentinfo 控制器中  获取家长的方法 和 获取学生的方法得到学生ID 去档案表中查询
        if(!empty($id)){
               $where = "id = 学生的ID";
                //根据学生ID 获取学生资料
               $studentInfo = Db::name('student')->where($where)->field('name,sex,place,birthday,home,once_garden,source_id')->find();
               if(!empty($studentInfo['source_id'])){
                   // 根据来源ID 获取来源信息
                     $source_id = $studentInfo['source_id'];
                     $where="id = $source_id"
                     $msg = Db::name('message_source')->where($where)->find();
                     $studentInfo['msg'] = $msg;
                     return $studentInfo; 
               }
        }
       
      
        
    }

    //获取最新通知消息
    public function getNewMsg()
    {

        Db::name('消息表')->field('添加时间，通知的消息')->order('id','DESC')->limit(1);
        $sql = Db::name()->getlastsql();
        dump($sql);
    }
    //获取通知消息
    public function getMsg()
    {
         //获取更多消息  展示当月
        $time = date("Y-m-d" ,strtotime("now"));
        $month = date("Y-m-00" ,strtotime("now"));
        $where = "addtime <= {$time} and addtime >= {$month}";
        $data = Db::name('消息表')->where($where)->field('添加时间，通知的消息')->select();
    }

    // 首页展示
    public function index()
    {
       
    }

    //获取膳食
    public function food()
    {   
          //应该是只获取当前家长孩子所在园区的营养膳食
         $food = Db::name('食物表')->select(); 
    }
  
}
