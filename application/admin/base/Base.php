<?php

namespace app\admin\base;

use think\Controller;
use think\Db;
use think\facade\Request;
use think\facade\Session;
/*
 * 公共基类
 */
class Base extends Controller {
    public function initialize(){
        parent::initialize();

        //判断用户是否登录
        $isLogin=$this->isLogin();
        if(!$isLogin) {
            //没登录跳转登录
            return $this->redirect('login/index');
        }else{
            //登录获取真实姓名传值
            $actualname=Session::get('actualname');
            $this->assign('actualname',$actualname);
            //登录获取用户名传值
            $user_name=Session::get('name');
            $this->assign('user_name',$user_name);
            //获取admin_id，查看管理员详情
            $this->assign('base_admin_id',Session::get('admin_id'));
            //获取网站名称
            $web_list = Db::name('setup')->where('id',1)->find();
            $this->assign('weblist',$web_list);
        }

        //根据权限获取menu
        $menu=$this->get_menu(Session::get('admin_id'));
        $this->assign('top_menu',$menu);
    }

    //判断用户是否登录方法
    public function isLogin() {
        $user=Session::get('name');
        if($user) {
            return true;
        }
        return false;
    }

    //根据权限获取menu
    public function get_menu($admin_id){
        //获取管理员信息
        $admin = Db::name('admin')->where('id',$admin_id)->find();

        //根据管理员信息获取角色信息
        $admin_role=Db::name('role')->where('id',$admin['role_id'])->find();

        //根据角色获取权限信息
        $admin_auth = Db::name('auth')->where('id','in',$admin_role['role_auth_id'])->where('auth_pid',0)->order('sort','asc')->select();
        //组合菜单

        $menu = [];
        foreach ($admin_auth as $k=>$v){
            if($v['auth_pid'] == 0){
                $menu[$k] = $v;
                $menu_where=[
                    'auth_pid'=>$v['id'],
                    'auth_level'=>1
                ];
                $menulist = Db::name('auth')->where($menu_where)->where('id','in',$admin_role['role_auth_id'])->select();
                $menu[$k]['menu_list2']=$menulist;
            }
        }
        return $menu;
    }

}