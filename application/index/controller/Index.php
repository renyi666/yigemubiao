<?php
namespace app\index\controller;

use app\index\model\Member;
use think\Controller;
use think\Request;

class Index extends Controller
{
    public function index()
    {

        return $this->fetch();

    }

    public function login()
    {

        $data = Request::instance();
        $list = $data->param();
        $memberM = new Member();
        $result = $memberM->login($list);


        //返回的不是true则说明登陆有问题。返回登录错误信息
        if ($result != "1") {

            return $this->showError($result);
        }

        return $result;


    }
    public function showError($code = 0)
    {
        $result['status'] = 'fail';
        $result['msg'] = 'fail';
        switch ($code) {
            case -100:
                $result['msg'] = '该用户不存在';
                break;
            case -101:
                $result['msg'] = '密码不正确';
                break;
            case -102:
                $result['msg'] = '登录失败，该用户已被禁用';
                break;
            case  -103:
                $result['msg'] = '创建小组失败';
                break;

            case  -104:
                $result['msg'] = '不存在该小组';
                break;
            case  -105:
                $result['msg'] = '加入小组失败';
                break;
            case  -106:
                $result['msg'] = '该小组中没有当前用户';
                break;
            case -107:
                $result['msg'] = '修改失败';
                break;
            case -108:
                $result['msg'] = '该用户已经存在';
                break;
            case  -109:
                $result['msg'] = '添加用户失败';
                break;
            case  -110:
                $result['msg'] = '增加成员到分组失败';
                break;
            case -111:
                $result['msg'] = '修改资料失败';
                break;
            case  -112:
                $result['msg']  =   '该用户已经在小组内';
                break;
            case  -113:
                $result['msg']  =   '事件添加失败';
                break;
            case  -114:
                $result['msg']  =   '该收藏已经存在';
                break;
            case  -115:
                $result['msg']  ='收藏失败';
                break;
            case -116:
                $result['msg']  =   '收藏状态变更失败';
                break;
        }
        return $result;

    }
}
