<?php

namespace app\admin\controller;
use think\Controller;
use think\Db;
/*
 * 异常跳转提示
 */
class Abnormal extends Controller
{
    //没有权限提示页
    public function noauthority(){
        return $this->fetch();
    }
}