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
 * @modified_time：2016/11/27 9:57
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


use app\index\model\GroupMember;
use app\index\model\Member;
use think\Request;

class Group extends Base
{


    public function index()
    {

        $this->isLogin();
        $groupM = new \app\index\model\Group();
        $groupMemberM = new GroupMember();

        //筛选当前用户所属的小组
        $uid = session('uid');
        $result = $groupMemberM->getAllByUser($uid);

        foreach ($result as $key => $value) {

            $data[$key] = $value['group_id'];

        }
        $data = implode(',', $data);
        $where['id'] = array('in', $data);
        unset($result);
        $result = $groupM->search($where);


        $this->assign('result', $result);
        return $this->fetch();
    }

    //新建小组
    public function createGroup()
    {
        $data = Request::instance();

        $list = $data->param();
        $list['charge_user'] = session('uid');

        $groupM = new \app\index\model\Group();
        $result = $groupM->createGroup($list);

        //创建失败返回错误信息
        if ($result <= 0) {

            return $this->showError($result);
        }

        return $result;
    }

    //加入小组
    public function joinGroup()
    {
        $data = Request::instance();
        $list = $data->param();
        $groupMemberM = new GroupMember();
        $result = $groupMemberM->joinGroup($list);

        if ($result <= 0) {

            return $this->showError($result);
        }

        return 1;
    }

    //修改离职在职状态
    public function editStatus()
    {

        $data = Request::instance();
        $list = $data->param();


        $groupMemberM = new GroupMember();
        $result = $groupMemberM->editStatus($list);

        if ($result <= 0) {

            return $this->showError($result);
        }
        return $result;
    }

    //显示小组所有人员
    public function getAllUser()
    {
        $data = Request::instance();
        $list = $data->param();
        $groupMemberM = new GroupMember();
        $result = $groupMemberM->getAllUser($list);


        return $result;


    }

    //小组增加成员
    public function addUser()
    {

        $data = Request::instance();
        $list = $data->param();
        $groupMemberM = new GroupMember();
        $result = $groupMemberM->addUser($list);

        if ($result <= 0) {
            return $this->showError($result);
        }
        return $result;


    }

    //选择小组后进去的第一个页面
    public function defaultPage()
    {//传递过来的参数应该包括小组id,在职状态
        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);


        $data = Request::instance();
        $list = $data->param();
        $groupMemberM = new GroupMember();

        $groupM = new \app\index\model\Group();

        $where['id'] = $list['group_id'];

        $group_result = $groupM->search($where);
        $this->assign('group_result', $group_result['0']);

        //吧小组信息放到session里
        session('groupInfo', $group_result['0']);

        $position_where['user_id'] = $userInfo['id'];
        $position_where['group_id'] = $list['group_id'];
        $position_result = $groupMemberM->getPosition($position_where);
        session('position_result', $position_result['position']);
        $this->assign('position_result', $position_result);

        //判断status的信息，，为了方便选择搜索
        if (!array_key_exists('status', $list)) {


            $list['status'] = 0;
            $this->assign('status', $list['status']);
            unset($list['status']);
        } else {

            $this->assign('status', $list['status']);
        }


        //若刚进去的时候没选状态，会自动清除status字段
        $list = array_filter($list);

        $result = $groupMemberM
            ->alias('g')
            ->where($list)
            ->field('g.user_id,g.position,g.status,m.nickname,m.mobile,m.id')
            ->join('member m', 'm.id=g.user_id')
            ->select();


        $this->assign('result', $result);
        return $this->fetch();

    }

}