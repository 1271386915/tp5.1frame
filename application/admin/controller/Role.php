<?php

namespace app\admin\controller;

use app\admin\Base\base;
use think\Db;
use think\facade\Session;

/*
 * 角色管理
 */

class Role extends Base
{

    public function index()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }

        return $this->fetch();
    }

    //添加角色
    public function add()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }

        //获取目录
        $this->assign('menulist', $this->get_menu_list());
        //根据用户获取权限

        return $this->fetch();
    }

    //保存角色
    public function save(){
        $code=0;
        $msg='啊哦，失败了...';

        $data=input('post.');
        $line=Db::table('role')->where('role_name',trim($data['name']))->count();
        if($line>0){
            $msg='啊哦，角色已存在...';
        }else{
            $list=[];
            $list['role_name']=$data['name'];
            $list['description']=$data['description'];
            $list['addtime']=time();
            $list['role_auth_id']=implode(',',$data['id']);
            $res=Db::table('role')->insert($list);
            if($res){
                $code=1;
                $msg='添加成功';
                return ['code'=>$code,'msg'=>$msg];
            }
        }
        return ['code'=>$code,'msg'=>$msg];
    }

    //修改角色
    public function edit()
    {
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }
        //获取目录
        $this->assign('menulist', $this->get_menu_list());
        //获取用户的权限
        $admin_role=Db::name('role')->where(['id' => input('get.')['id']])->find();

        $admin_role['role_auth_id'] =  explode(",", $admin_role['role_auth_id']);
        $this->assign('admin_role', $admin_role);
        return $this->fetch();
    }

    //保存修改
    public function update(){
        $code=0;
        $msg="更新失败";
        $data=input('post.');
        $res=[];
        $info=Db::table('role')->where('id',$data['id'])->find();
        if($info){
            $res['role_auth_id']=implode(',',$data['role_id']);
            $res['id']=$data['id'];
            $res['description']=$data['description'];
            $res['role_name']=$data['name'];
            $res=Db::table('role')->where('id',$data['id'])->update($res);
            if($res){
                $code=1;
                $msg="更新成功";
            }else{
                $msg="更新失败";
            }
        }else{
            $msg='对不起没查询到角色...';
        }
        return ['code'=>$code,'msg'=>$msg];
    }

    //删除
    public function del(){
        //判断用户是否有权限
        $Authority=is_Authority();
        if(!$Authority){
            return ['code'=>0,'msg'=>'没有权限~'];
        }

        $code=0;
        $msg="删除失败";

        $data=input('post.');
        $id=$data['id'];
        if($id!=''){
            $info=Db::table('role')->where('id',$id)->find();
            if($info){
                $res=Db::table('role')->where('id',$info['id'])->delete();
                if($res){
                    $code=1;
                    $msg="删除成功";
                }
            }else{
                $msg="啊哦，用户不存在...";
            }
        }else{
            $msg="ID不正确";
        }
        return ['code'=>$code,'msg'=>$msg];
    }

    //查看角色详情
    public function view(){
        //判断用户是否有权限
        $Authority = is_Authority();
        if (!$Authority) {
            return $this->redirect('abnormal/noauthority');
        }
        $id = input('get.id');
        $roel =Db::name('role')->where('id',$id)->find();
        $auth = Db::name('auth')->where('id','in',$roel['role_auth_id'])->where('auth_pid',0)->select();

        $auth_list=[];
        foreach ($auth as $key=>$value){
            $where=[
                ['id','in',$roel['role_auth_id']],
                ['auth_pid','=',$value['id']]
            ];
            $auth_nume=Db::name('auth')->where($where)->select();
            $auth_list[$key] = $value;
            $auth_list[$key]['auth_role_list'] = $auth_nume;

        }
        $roel['auth_list']=$auth_list;
        $this->assign('info',$roel);

        return $this->fetch();
    }

    //前端数据表格获取列表
    public function getrolelist()
    {
        $data = $this->request->param();

        $map = $this->ifsearch($data);

        $page = isset($data['page']) ? $data['page'] : 1;
        $page = intval($page);
        $limit = isset($data['limit']) ? $data['limit'] : 1;
        $limit = intval($limit);
        $start = $limit * ($page - 1);

        if ($map == '') {

            $list = Db::name('role')->limit($start, $limit)->select();
            $count = Db::name('role')->count();
        } else {

            $list = Db::name('role')->where($map)->limit($start, $limit)->select();
            $count = Db::name('role')->where($map)->count();
        }

        foreach ($list as $key => $value) {
            $list[$key]['addtime'] = date('Y-m-d', $list[$key]['addtime']);
        }

        $data['code'] = 0;
        $data['msg'] = '';
        $data['count'] = $count;
        $data['data'] = $list;
        return json($data);

    }

    //搜索条件
    protected function ifsearch($data)
    {
        $map = [];

        $data['title'] = isset($data['title']) ? $data['title'] : '';

        if ($data['title'] != '') {
            $data['title'] = trim($data['title']);
            $num = Db::name('role')->where([['role_name', 'like', '%' . $data['title'] . '%']])->count();
            if ($num) {
                $map[] = ['role_name', 'like', '%' . $data['title'] . '%'];
            } else {
                $map[] = ['role_name', 'like', '%' . $data['title'] . '%'];
            }
        }
        if (count($map) == 0) {
            return $map = '';
        } else {
            return $map;
        }
    }

    //获取目录
    protected function get_menu_list()
    {
        //顶级目录
        $menu = Db::name('auth')->where(['auth_pid' => 0, 'auth_level' => 0])->select();
        $menulist = [];
        foreach ($menu as $k => $v) {
            $menu1 = Db::name('auth')->where(['auth_pid' => $v['id']])->select();
            $menulist[$k] = $v;
            $menulist[$k]['menu_list'] = $menu1;
            foreach ($menu1 as $key=>$value){
                $menu2 = Db::name('auth')->where(['auth_pid' => $value['id']])->select();
                foreach ($menu2 as $a=>$b){
                    array_push($menulist[$k]['menu_list'],$b);
                }
            }
        }
        return $menulist;
    }

}