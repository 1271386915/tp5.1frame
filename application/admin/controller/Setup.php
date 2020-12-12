<?php
namespace app\admin\controller;
use app\admin\base\Base;
use think\Db;

/*
 * 网站后台首页，默认有权限
 */
class Setup extends Base{

    public function index(){
        $config = Db::table('setup')->where('id', 1)->find();
        $config['update_time'] =  date('Y-m-d H:i:s', time());
        $this->assign('config',$config);
        return $this->fetch();
    }

    public function save(){
        $code=0;
        $msg="更新失败";
        $date=input('post.');
        $date['is_show']=isset($date['is_show'])?$date['is_show']:1;
        if($date['is_show']==1){
            $date['is_show']=1;
        }else{
            $date['is_show']=0;
        }
        $date['update_time'] = time();
        $res=Db::table('setup')->where('id',1)->update($date);
        if($res){
            $code=1;
            $msg="更新成功";
        }
        return ['code'=>$code,'msg'=>$msg];
    }
}