<?php
namespace app\admin\controller;
use app\admin\base\Base;
use think\Db;
use think\facade\Session;

/*
 * 网站后台首页，默认有权限
 */
class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function welcome(){
        $admin_role_id = Db::table('admin')->where('id', Session::get('admin_id'))->value('role_id');
        $role_name = Db::table('role')->where('id', $admin_role_id)->value('role_name');
        $this->assign('role_name',$role_name);
        return $this->fetch();
    }

    //查看详情
    public function view()
    {
        $id = input('get.id');
        $data = Db::table('admin')->where('id', $id)->find();
        $role_name = Db::table('role')->where('id', $data['role_id'])->value('role_name');
        $data['role_name'] = $role_name;
        $this->assign('info', $data);
        return $this->fetch();
    }
}
