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
 * @modified_time：2016/11/27 10:16
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */
namespace app\index\model;

use think\Db;
use think\Model;

class GroupMember extends Model
{

    protected $autoWriteTimestamp = true;

    //加入小组
    public function joinGroup($parm)
    {

        $where['id'] = $parm['group_id'];
        $groupM = new Group();
        $result = $groupM->where($where)->find();
        //小组不存在
        if ($result == null || $result == '') {

            return -104;
        }
        unset($result);
        $parm['user_id']    =   session('uid');

        //先判断数据表里是否已经加入本小组,没有的话再添加
        $data   =   $this->where($parm)->find();
        if($data==null){

            $result = $this->insertGetId($parm);
            if ($result == 0) {

                return -105;
            }
        }


        return 1;
    }

    //修改离职在职状态
    public function editStatus($parm)
    {

        $result = $this->where($parm)->find();
        if ($result == null || $result == '') {

            return -106;
        }

        //分情况更新
        $where['id']    =   $result['id'];
        if ($result['status'] =="1") {

            $list['status'] =2;

            $update_result = $this->where($where)->update($list);
            if ($update_result == 0) {

                return -107;
            }
        } else {

            $list['status'] = "1";

            $update_result = $this->where($where)->update($list);
            if ($update_result == 0) {

                return -107;
            }
        }

        return $update_result;

    }

    //查询小组内所有组员
    public function getAllUser($parm)
    {

        return $this->field('user_id')->where($parm)->select();
    }

    //小组内增加成员
    public function addUser($parm)
    {//parm里边应该含有mobile和group_id参数

        $memberM = new Member();
        $where['mobile'] = $parm['mobile'];
        $member_result = $memberM->where($where)->find();
        Db::startTrans();

        if ($member_result==null) {

            $insert_result = $memberM->insertGetId($where);

            if ($insert_result <= 0) {
                Db::rollback();
                return -109;

            }
            $data['user_id'] = $insert_result;
        }else{
            $data['user_id'] = $member_result['id'];

        }



        $data['group_id'] = $parm['group_id'];
        $group_member_return    =$this->where($data)->find();
        if($group_member_return!=null){

            Db::rollback();
            return -112;
        }

        $group_member_result = $this->insertGetId($data);
        if ($group_member_result <= 0) {
            Db::rollback();
            return -110;
        }

        Db::commit();
        return 1;
    }

    // 根据id筛选离职的小组
    public function selectOutById($list)
    {

        $parm['user_id'] = $list;
        $parm['status'] = 2;
        $result = $this->where($parm)->select();
        return $result;

    }
    //筛选自己所属小组
    public  function  getAllByUser($parm){

        $list['user_id'] =   $parm;
        $list['status']  =   1;
        $result =   $this->field('group_id')->where($list)->select();
        return $result;
    }
    //筛选小组旗下的人

        public  function  getUser($parm){

            return $this->where($parm)->select();

        }

        //查找登录用户的权限(是否是组长或者组员)
    public function getPosition($parm){
        return $this->field('position')->where($parm)->find();

    }

}