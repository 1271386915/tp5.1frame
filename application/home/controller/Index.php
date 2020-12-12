<?php
// @Time    : 2020/12/12 2:38 下午
// @Author  : Youyandong
// @File    : ${NAME}.py
// @Software: PhpStorm
// @use:


namespace app\home\controller;

use think\Controller;
/*
 * 网站后台首页，默认有权限
 */

class Index extends Controller
{
    public function index()
    {
        print("这里是首页");
    }
}
