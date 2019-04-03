<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\api\controller\Base;

class Teachergardh extends Base
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
       |++ Interested friends can add me QQ:11*69*35*8   ---->Cracking a digit   ----->You'll get me.                 ++|
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
            家园共育 老师端添加  
         */
     public function create(Request $request)
     { 
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
