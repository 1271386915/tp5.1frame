<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\facade\Session;
use think\Request;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    //登录验证

    public function login(Request $request)
    {
        $data = input('post.');
        //print_r($data);
        $status = 0;
        $msg = '登录失败';

        if (trim($data['name']) != '' && trim($data['password']) != '') {
            //使用用户名和真实姓名登录
            if (Db::table('admin')->where('name', $data['name'])->find()) {
                $admin = Db::table('admin')->where('name', $data['name'])->find();
            } else if (Db::table('admin')->where('actualname', $data['name'])->find()) {
                $admin = Db::table('admin')->where('actualname', $data['name'])->find();
            } else if (Db::table('admin')->where('phone', $data['name'])->find()) {
                $admin = Db::table('admin')->where('phone', $data['name'])->find();
            } else {
                $admin = false;
            }
            if ($admin) {
                //判断用户状态
                if ($admin['status'] == 0) {
                    //验证密码
                    if (trim(md5($data['password']) == $admin['password'])) {
                        $IP = get_ip();//获取登录ip
                        //修改最近登录ip
                        $updIP = true;
                        if ($IP != $admin['IP_login']) {
                            $updIP = Db::table('admin')->where('id', $admin['id'])->update(['IP_login' => $IP]);
                        }
                        //修改最近登录时间
                        $updTime = Db::table('admin')->where('id', $admin['id'])->update(['logintime' => time()]);
                        if ($updIP && $updTime) {
                            Session::set('name', $admin['name']);//用户名
                            Session::set('actualname', $admin['actualname']);//真实姓名
                            Session::set('admin_id', $admin['id']);//管理员id
                            $status = 1;
                            $msg = '登录成功';
                        }
                    } else {
                        $msg = '用户名不正确或密码不正确';
                    }
                } else {
                    $msg = '用户已禁用，请联系管理员';
                }
            } else {
                $msg = '用户不存在';
            }
        } else {
            $msg = '用户名和密码不能为空';
        }
        return ['code' => $status, 'msg' => $msg];
    }

    //退出
    public function loginout()
    {
        Session::delete('name');
        Session::delete('actualname');
        return redirect(url('login/index'));
    }
}