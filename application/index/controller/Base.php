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
 * @modified_time：2016/11/27 9:21
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


use app\index\model\Group;
use app\index\model\GroupMember;
use think\Controller;
use think\Db;
use think\Request;

class Base extends Controller
{

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->isLogin();
        $position_result = session('position_result');
        if ($position_result == null || $position_result == '') {

            $position_result == 2;
        }

        $this->isGroup();
        //判断是否有权限进入本页
        $this->auth_page();
        $this->getGroup();
        $this->assign('position_result', $position_result);
    }


    //返回错误码
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
                $result['msg'] = '该用户已经在小组内';
                break;
            case  -113:
                $result['msg'] = '事件添加失败';
                break;
            case  -114:
                $result['msg'] = '该收藏已经存在';
                break;
            case  -115:
                $result['msg'] = '收藏失败';
                break;
            case -116:
                $result['msg'] = '收藏状态变更失败';
                break;
            case -117:
                $result['msg'] = '更新事件失败';
                break;
            case  -118:
                $result['msg'] = '日报增加失败';
                break;
            case  -119:
                $result['msg'] = '该日期已经存在';
                break;
            case -120:
                $result['msg'] = "添加失败";
                break;
        }
        return $result;

    }


    //判断是否登录
    public function isLogin()
    {
        $user = session('uid');

        if (session('?uid')) {


        } else {

            $this->redirect('Index/index');
        }

    }

    public function getGroupInfo($parm)
    {

        $groupM = new Group();
        $result = $groupM->search($parm);
        if (isset($result) && $result != '' && $result != null) {


            return $result['0'];
        }


    }

    //返回成功信息
    public function successReturn()
    {
        $list['status'] = "success";
        $list['msg'] = '成功';
        return $list;

    }

    //判断是否可以访问本业
    public function auth_page()
    {


        $request = Request::instance();
        $data_result = $request->param();

        if (isset($data_result['group_id']) && is_numeric($data_result['group_id'])) {

            $groupMemberM = new GroupMember();

            $parm['status'] = 1;
            $result = $groupMemberM->getUser($parm);
            if ($result == null) {

                $this->redirect('Baocuo/index');
            }
            foreach ($result as $k => $value) {

                $list[$k] = $value['user_id'];

            }

            $uid = session('uid');
            if (in_array($uid, $list)) {


            } else {

                $this->redirect('Baocuo/index');
            }


        }

    }

    /**
     * 获小组的详细信息
     */
    public function getGroup()
    {
        $where['id'] = input('group_id');

        if (isset($where['id']) && $where['id'] != null && $where['id'] != "") {

            $groupM = new Group();
            $groupInfo = $groupM->search($where);

            //判断数据库有没有这个小组的信息
            if($groupInfo==null||$groupInfo==""||$groupInfo['0']==null||$groupInfo['0']==""){

                redirect("Baocuo/index");
            }

            $this->assign('groupInfo', $groupInfo['0']);
        }

    }

    public function isGroup()
    {
        $where['id'] = input('group_id');

        if (isset($where['id']) && $where['id'] != null && $where['id'] != "") {

            $groupM = new Group();
            $groupInfo = $groupM->search($where);
            if ($groupInfo == null || $groupInfo == "") {

                $this->redirect('Baocuo/index');

            }
        }

    }

}