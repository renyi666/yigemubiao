<?php
/***
 *
 *                        ,%%%%%%%%,
 *                      ,%%/\%%%%/\%%
 *                     ,%%%\c "" J/%%%
 *            %.       %%%%/ o  o \%%%
 *            `%%.     %%%%    _  |%%%
 *             `%%     `%%%%(__Y__)%%'
 *             //       ;%%%%`\-/%%%'
 *            ((       /  `%%%%%%%'
 *             \\    .'          |
 *              \\  /       \  | |
 *               \\/         ) | |
 *                \         /_ | |__
 *                (___________))))))) 攻城狮
 *
 * @author：gaoyuan
 * @modified_time：2016/11/27 9:20
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


use think\Request;

class Member extends Base
{

    public function index()
    {
        return $this->fetch();

    }

    //登录功能


    //修改手机号和密码

    public function editUser()
    {
        $this->isLogin();
        $data = Request::instance();
        $list = $data->param();
        $list['id'] = session('uid');
        $memberM = new \app\index\model\Member();

        $result = $memberM->editUser($list);
        if ($result == 0) {

            return $this->showError(-111);
        }

        return 1;


    }

    //此页面显示用户修改密码等页面
    public function userData()
    {
        $group_result   =session('groupInfo');
        $this->assign('group_result',$group_result);
        $this->isLogin();
        $where['id'] = session('uid');
        $memberM = new \app\index\model\Member();



        $result = $memberM->getUser($where);
        $this->assign('result',$result);

        return $this->fetch();

    }
}