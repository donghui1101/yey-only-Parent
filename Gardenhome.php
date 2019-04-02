<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\api\controller\Basics;

class Gardenhome extends Basics
{
    /**
     * garden_spare  controller
     *
     * @return \think\Response
     */
    /*
          家园共育
    */

   //家长观看
    public function index()
    { 

        //获取家园共育的信息   根据班级
          $where = "class_id =$class_id";
          $data = Db::name('garden_home')->where($where)->select()
          if($data){
               $msg = '获取数据成功';
               rData('1','成功',$msg);
          }else{
               $msg = '获取数据失败了';
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
    //老师添加  这段代码应该写到老师端（PC）家长只能看 不能添加
    public function create(Request $request)
    { 
          // 感觉此处应该获取老师所在的集团、园区、班级  这应该是个bug
          // 家园共育  添加模块
          $files = $request->file('files');
          $res = $request->only('admin_id,title,');
          if(empty($files)){
              $msg = '请上传图片或者视频';
              rData('0','失败',$msg);
          }
          $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
          $path = $info->getSaveName();
          $res['desc'] = $path;
          $res['staff_id'] = $res['admin_id'];
          $rows = count($res);
          if($rows >='2'){
             $time = date("Y-m-d",strtotime("now"));
             $res['addtime'] = $time; 
             $ret = Db::name('garden_home')->insert($res);
             if($ret){
                 $msg = '添加成功';
                 rData('1','成功');
             }else{
                 $msg = '添加数据库失败，请重新添加';
                 rData('0','失败',$msg);
             } 
          }else{
              $msg = '请填写完整信息！';
              rData('0','添加失败',$msg);
          }

    }
}
