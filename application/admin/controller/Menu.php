<?php

namespace app\admin\controller;

use app\admin\Base\base;
use think\Db;

/*
 * 管理员管理
 */

class Menu extends Base
{
    public function index()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }

        $menu = [];
        $menu1_where = [
            ['auth_pid', '=', 0],
            ['auth_level', '=', 0]
        ];
        $menu1 = Db::name('auth')->where($menu1_where)->order('sort', 'asc')->select();
        foreach ($menu1 as $item => $v) {
            $menu2_where = [
                ['auth_pid', '=', $v['id']],
                ['auth_level', '=', 1]
            ];
            $menu2 = Db::name('auth')->where($menu2_where)->select();
            $menu[$item] = $v;
            $menu[$item]['menu2'] = $menu2;
            foreach ($menu2 as $k => $value) {
                $menu3_where = [
                    ['auth_pid', '=', $value['id']],
                    ['auth_level', '=', 2]
                ];
                $menu3 = Db::name('auth')->where($menu3_where)->select();
                $menu[$item]['menu2'][$k]['menu3'] = $menu3;
            }
        }
        $this->assign('menu', $menu);
        return $this->fetch();
    }

    //添加顶级目录
    public function add1()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }

        return $this->fetch();
    }

    //保存顶级目录
    public function save1()
    {
        $code = 0;
        $msg = '失败了~';

        $data = input('post.');
        if (!is_numeric($data['sort'])) {
            $msg = '排序只能为数字~';
            return ['code' => $code, 'msg' => $msg];
        }
        $auth_name = Db::table('auth')->where('auth_name', $data['auth_name'])->count();
        if ($auth_name) {
            $msg = '名称已存在~';
            return ['code' => $code, 'msg' => $msg];
        }

        $res = [];
        $res['auth_name'] = $data['auth_name'];
        $res['sort'] = $data['sort'];
        $res['icon_font'] = $data['icon_font'];
        $res['auth_pid'] = 0;
        $res['auth_controller'] = '';
        $res['auth_method'] = '';
        $res['auth_path'] = '';
        $res['auth_level'] = '';

        //添加
        $status = Db::table('auth')->insert($res);
        if ($status) {
            $code = 1;
            $msg = '添加成功';
        }

        return ['code' => $code, 'msg' => $msg];
    }

    //添加二级目录
    public function add2()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }

        $parent_id = input('get.id');
        $this->assign('parent_id', $parent_id);
        return $this->fetch();
    }

    //保存二级目录
    public function save2()
    {
        $code = 0;
        $msg = '失败了~';

        $data = input('post.');
        $res = [];
        $res['auth_name'] = $data['auth_name'];
        $res['sort'] = '';
        $res['icon_font'] = '';
        $res['auth_pid'] = $data['auth_pid'];
        $res['auth_path'] = $data['auth_path'];
        $res['auth_controller'] = explode("/", $data['auth_path'])[0];
        $res['auth_method'] = explode("/", $data['auth_path'])[1];
        $res['auth_level'] = 1;

        //添加
        $status = Db::table('auth')->insert($res);
        if ($status) {
            $code = 1;
            $msg = '添加成功';
        }

        return ['code' => $code, 'msg' => $msg];
    }

    //添加三级目录
    public function add3()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }

        $parent_id = input('get.id');
        $this->assign('parent_id', $parent_id);
        return $this->fetch();
    }

    //保存三级目录
    public function save3()
    {
        $code = 0;
        $msg = '失败了~';

        $data = input('post.');
        $res = [];
        $res['auth_name'] = $data['auth_name'];
        $res['sort'] = '';
        $res['icon_font'] = '';
        $res['auth_pid'] = $data['auth_pid'];
        $res['auth_path'] = $data['auth_path'];
        $res['auth_controller'] = explode("/", $data['auth_path'])[0];
        $res['auth_method'] = explode("/", $data['auth_path'])[1];
        $res['auth_level'] = 2;

        //添加
        $status = Db::table('auth')->insert($res);
        if ($status) {
            $code = 1;
            $msg = '添加成功';
        }

        return ['code' => $code, 'msg' => $msg];
    }
    //修改菜单
    public function edit()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }

        $id = input('get.id');
        $auth = Db::table('auth')->where('id', $id)->find();
        $this->assign('auth', $auth);
        return $this->fetch();
    }

    //保存个性的菜单
    public function update()
    {
        $code = 0;
        $msg = "更新失败";

        $data = input('post.');
        $res = [];
        if ($data['auth_pid'] == 0) {
            if (!is_numeric($data['sort'])) {
                $msg = '排序只能为数字~';
                return ['code' => $code, 'msg' => $msg];
            }
            $res['auth_name']=$data['auth_name'];
            $res['icon_font']=$data['icon_font'];
            $res['sort']=$data['sort'];
            $res['auth_level']=0;
            $res['auth_pid']=0;
        }else{
            $res['auth_name']=$data['auth_name'];
            $res['auth_path']=$data['auth_path'];
            $res['auth_controller'] = explode("/", $data['auth_path'])[0];
            $res['auth_method'] = explode("/", $data['auth_path'])[1];
        }
        $edit = Db::table('auth')->where('id', $data['id'])->update($res);
        if ($edit) {
            $code = 1;
            $msg = "更新成功";
        } else {
            $msg = "更新失败";
        }
        return ['code' => $code, 'msg' => $msg];
    }

    //删除菜单
    public function del()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return ['code' => 0, 'msg' => '没有权限~'];
        }

        $code = 0;
        $msg = '失败了~';

        $id = input('post.id');
        $menu = Db::table('auth')->where('auth_pid', $id)->count();

        if ($menu) {
            $msg = '有子目录不能删除~';
        } else {
            $res = Db::table('auth')->where('id', $id)->delete();
            if ($res) {
                $code = 1;
                $msg = '删除成功~';
            }
        }
        return ['code' => $code, 'msg' => $msg];
    }
}