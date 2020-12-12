<?php

/**
 * 判断用户是否有权限操作
 *
 */
function is_Authority(){
    $controller=request()->controller();//获取控制器名称
    $action=request()->action();//获取操作方法名称
    $path = lcfirst($controller).'/'.$action;//完整链接

    $admin_id=Session::get('admin_id');
    $role_id = Db::name('admin')->where('id',$admin_id)->value('role_id');

    //根据管理员信息获取角色信息
    $admin_role=Db::name('role')->where('id',$role_id)->find();

    //根据角色获取权限信息
    $admin_auth = Db::name('auth')->where('id','in',$admin_role['role_auth_id'])
        ->where('auth_path',$path)->find();
    if($admin_auth){
        return true;
    }else{
        return false;
    }
}

/**
 * 获取IP地址
 *
 * @return string
 */
function get_ip() {
    $realip = '';
    $unknown = 'unknown';
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($arr as $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $realip = $ip;
                    break;
                }
            }
        } else if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
            $realip = $_SERVER['REMOTE_ADDR'];
        } else {
            $realip = $unknown;
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)) {
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)) {
            $realip = getenv("REMOTE_ADDR");
        } else {
            $realip = $unknown;
        }
    }
    $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;
    return $realip;
}

/**
 * 获取权限 已经放弃
 * @return true false
 */
//function get_Authority_common($admin_id,$path){
//    //查询管理员角色id
//    $role_id = Db::name('admin')->where('id',$admin_id)->value('role_id');
//
//    //根据管理员信息获取角色信息
//    $admin_role=Db::name('role')->where('id',$role_id)->find();
//
//    //根据角色获取权限信息
//    $where = [
//        'id'=>$admin_role['role_auth_id'],
//        'auth_path'=>$path
//    ];
//    $admin_auth = Db::name('auth')->where('id','in',$admin_role['role_auth_id'])
//        ->where('auth_path',$path)->find();
//    if($admin_auth){
//        return true;
//    }else{
//        return false;
//    }
//}