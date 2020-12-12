<?php

namespace app\admin\controller;

use app\admin\Base\base;
use think\Db;

/*
 * 管理员管理
 */

class Admin extends Base
{

    public function index()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }

        //角色
        $role = Db::table('role')->select();
        $this->assign('role', $role);
        return $this->fetch();
    }

    //前端数据表格获取列表
    public function getadminlist()
    {
        $data = $this->request->param();

        $map = $this->ifsearch($data);//组合条件

        $page = isset($data['page']) ? $data['page'] : 1;
        $page = intval($page);
        $limit = isset($data['limit']) ? $data['limit'] : 1;
        $limit = intval($limit);
        $start = $limit * ($page - 1);

        if ($map == '') {

            $list = Db::name('admin')->limit($start, $limit)->select();
            //echo Db::name('admin')->limit($start, $limit)->getLastSql();
            $count = Db::name('admin')->count();
        } else {

            $list = Db::name('admin')->where($map)->limit($start, $limit)->select();
            //echo Db::name('admin')->where($map)->limit($start, $limit)->getLastSql();
            $count = Db::name('admin')->where($map)->count();
        }

        foreach ($list as $key => $value) {

            $list[$key]['addtime'] = date('Y-m-d', $list[$key]['addtime']);

            if ($list[$key]['logintime'] == 0) {
                $list[$key]['logintime'] = date('Y-m-d H:i:s', time());
            } else {
                $list[$key]['logintime'] = date('Y-m-d H:i:s', $list[$key]['logintime']);
            }

            //获取角色名称
            $list[$key]['role_name'] = Db::name('role')->where('id', $list[$key]['role_id'])->value('role_name');

        }

        $data['code'] = 0;
        $data['msg'] = '';
        $data['count'] = $count;
        $data['data'] = $list;
        return json($data);

    }

    //添加管理员
    public function add()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }
        //角色
        $role = Db::table('role')->select();
        $this->assign('role', $role);
        return $this->fetch();
    }

    //保存添加管理员
    public function save()
    {
        $code = 0;
        $msg = '失败了~';

        $data = input('post.');
        //组合数据
        $res = [];
        //判断用户名与真实姓名是否重复
        $name = Db::name('admin')->where('name', $data['username'])->count();
        if (!$name) {
            $res['name'] = $data['username'];
        } else {
            $msg = '用户名已存在~';
            return ['code' => $code, 'msg' => $msg];
        }
        $name = Db::name('admin')->where('actualname', $data['actualname'])->count();
        if (!$name) {
            $res['actualname'] = $data['actualname'];
        } else {
            $msg = '真实姓名已存在~';
            return ['code' => $code, 'msg' => $msg];
        }
        //身份证
        if ($data['id_card'] != '') {
            $res['id_card'] = $data['id_card'];
        } else {
            $msg = '身份证不能为空~';
            return ['code' => $code, 'msg' => $msg];
        }
        //密码
        if ($data['pass'] != '') {
            $res['password'] = md5($data['pass']);
        } else {
            $msg = '密码不能为空~';
            return ['code' => $code, 'msg' => $msg];
        }
        //手机号
        if ($data['phone'] != '') {
            $res['phone'] = $data['phone'];
        } else {
            $msg = '手机号不能为空~';
            return ['code' => $code, 'msg' => $msg];
        }
        //邮箱
        if ($data['email'] != '') {
            $res['email'] = $data['email'];
        } else {
            $msg = '邮箱不能为空~';
            return ['code' => $code, 'msg' => $msg];
        }
        //地址
        if ($data['address'] != '') {
            $res['address'] = $data['address'];
        } else {
            $msg = '地址不能为空~';
            return ['code' => $code, 'msg' => $msg];
        }
        //角色
        if ($data['role'] != '') {
            $res['role_id'] = $data['role'];
        } else {
            $msg = '角色不能为空~';
            return ['code' => $code, 'msg' => $msg];
        }
        //年龄
        $res['age'] = $data['age'];
        //状态
        $status = isset($data['status']) ? $data['status'] : '';
        if ($status == 'on') {
            $res['status'] = 0;
        } else {
            $res['status'] = 1;
        }
        $res['addtime'] = time();

        $res = Db::table('admin')->insert($res);
        if ($res) {
            $code = 1;
            $msg = '添加成功';
        }
        return ['code' => $code, 'msg' => $msg];
    }

    //修改
    public function edit()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }

        $code = 0;
        $msg = '失败了~';

        $id = input("get.id");
        if ($id) {
            $info = Db::table('admin')->where('id', $id)->find();
            if ($info) {
                $role = Db::table('role')->select();
                $this->assign('role', $role);
                $this->assign('info', $info);
                return $this->fetch();
            } else {
                $msg = '对不起没查询到管理员~';
                return ['code' => $code, 'msg' => $msg];
            }
        } else {
            $msg = '失败了~';
        }
        return ['code' => $code, 'msg' => $msg];
    }

    //保存修改
    public function update()
    {
        $code = 0;
        $msg = "更新失败";

        $data = input('post.');
        $res = [];

        $admin = Db::name('admin')->where('id', $data['id'])->count();
        if ($admin) {
            //密码
            if ($data['pass'] != '') {
                $res['password'] = md5($data['pass']);
            }
            //手机号
            if ($data['phone'] != '') {
                $res['phone'] = $data['phone'];
            } else {
                $msg = '手机号不能为空~';
                return ['code' => $code, 'msg' => $msg];
            }
            //邮箱
            if ($data['email'] != '') {
                $res['email'] = $data['email'];
            } else {
                $msg = '邮箱不能为空~';
                return ['code' => $code, 'msg' => $msg];
            }
            //地址
            if ($data['address'] != '') {
                $res['address'] = $data['address'];
            } else {
                $msg = '地址不能为空~';
                return ['code' => $code, 'msg' => $msg];
            }
            //角色
            if ($data['role'] != '') {
                $res['role_id'] = $data['role'];
            } else {
                $msg = '角色不能为空~';
                return ['code' => $code, 'msg' => $msg];
            }
            //年龄
            $res['age'] = $data['age'];
            //状态
            $status = isset($data['status']) ? $data['status'] : '';
            if ($status == 'on') {
                $res['status'] = 0;
            } else {
                $res['status'] = 1;
            }

            //修改
            $res = Db::table('admin')->where('id', $data['id'])->update($res);
            if ($res) {
                $code = 1;
                $msg = "更新成功";
            } else {
                $msg = "更新失败";
            }
        }
        return ['code' => $code, 'msg' => $msg];
    }

    //查看详情
    public function view()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return ['code' => 0, 'msg' => '没有权限~'];
        }

        $id = input('get.id');

        $data = Db::table('admin')->where('id', $id)->find();
        $role_name = Db::table('role')->where('id', $data['role_id'])->value('role_name');
        $data['role_name'] = $role_name;
        $this->assign('info', $data);
        return $this->fetch();
    }

    //删除管理员
    public function del()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return ['code' => 0, 'msg' => '没有权限~'];
        }

        $code = 0;
        $msg = "删除失败~";

        $id = input('post.id');
        if ($id != '') {
            $info = Db::table('admin')->where('id', $id)->find();
            if ($info) {
                $res = Db::table('admin')->where('id', $info['id'])->delete();
                if ($res) {
                    $code = 1;
                    $msg = "删除成功";
                }
            } else {
                $msg = "用户不存在~";
                return ['code' => $code, 'msg' => $msg];
            }
        } else {
            $msg = "用户ID不正确";
            return ['code' => $code, 'msg' => $msg];
        }
        return ['code' => $code, 'msg' => $msg];
    }

    //批量删除
    public function delall()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }

        $code = 0;
        $msg = "更新失败";

        $id = input('post.')['id'];
        $str = substr($id, 0, strlen($id) - 1);

        $res = Db::table('admin')->where("id in ($str)")->delete();

        if ($res) {
            $code = 1;
            $msg = "删除成功";
        }

        return ['code' => $code, 'msg' => $msg];
    }

    //用户停用启用
    public function status()
    {
        $id = input('post.id');
        $status = input('post.status');
        $code = 0;
        $msg = '失败了~';
        if ($status == 1) {
            $res = Db::table('admin')->where('id', $id)->update(['status' => 1]);
        } else {
            $res = Db::table('admin')->where('id', $id)->update(['status' => 0]);
        }
        if ($res) {
            $code = 1;
            $msg = '操作成功';
        }
        return ['code' => $code, 'msg' => $msg];
    }

    //修改单元格数据
    public function table_edit()
    {
        $code = 0;
        $msg = '失败了~';

        $data = input('post.');
        $id = $data['id'];//id
        $field = $data['field'];//字段名
        $value = $data['value'];//修改内容

        $res = Db::table('admin')->where('id', $id)->update([$field => $value]);
        if ($res) {
            $code = 1;
            $msg = '成功~';
        }

        return ['code' => $code, 'msg' => $msg];
    }

    //搜索条件
    public function ifsearch($data)
    {
        $map = [];

        //关键词搜索
        $data['title'] = isset($data['title']) ? $data['title'] : '';
        if ($data['title'] != '') {
            $data['title'] = trim($data['title']);
            $num = Db::name('admin')->where([['name', 'like', '%' . $data['title'] . '%']])->count();
            if ($num) {
                $map[] = ['name', 'like', '%' . $data['title'] . '%'];
            } else if (Db::name('admin')->where([['phone', 'like', '%' . $data['title'] . '%']])->count()) {
                $map[] = ['phone', 'like', '%' . $data['name'] . '%'];
            } else if (Db::name('admin')->where([['address', 'like', '%' . $data['title'] . '%']])->count()) {
                $map[] = ['address', 'like', '%' . $data['title'] . '%'];
            } else if (Db::name('admin')->where([['id_card', 'like', '%' . $data['title'] . '%']])->count()) {
                $map[] = ['id_card', 'like', '%' . $data['title'] . '%'];
            } else if (Db::name('admin')->where([['actualname', 'like', '%' . $data['title'] . '%']])->count()) {
                $map[] = ['actualname', 'like', '%' . $data['title'] . '%'];
            } else {
                $map[] = ['name', 'like', '%' . $data['title'] . '%'];
            }
        }
        //角色选择
        $data['roleid'] = isset($data['roleid']) ? $data['roleid'] : '';
        if ($data['roleid'] != '') {
            $map[] = ['role_id', '=', $data['roleid']];
        }

        if (count($map) == 0) {
            return $map = '';
        } else {
            return $map;
        }
    }
}

