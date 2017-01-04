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
namespace app\index\model;

use think\Db;
use think\Model;

class Group extends Model
{

    protected $autoWriteTimestamp = true;

    //创建小组

    public function createGroup($parm)
    {//Parm应含有name,组长id等参数

        Db::startTrans();
        $result = $this->insertGetId($parm);


        //创建小组失败
        if ($result == 0) {
            Db::rollback();
            return -103;

        }
        //创建小组之后，自动加入小组，身份为组长
        $data['group_id'] = $result;
        $data['user_id'] = session('uid');
        $data['position'] = 1;
        $groupMemberM = new GroupMember();
        $join_result = $groupMemberM->joinGroup($data);
        //加入失败，返回错误信息
        if ($join_result < 0) {
            Db::rollback();
            return $join_result;
        }

        Db::commit();
        return 1;
    }

    //搜索所有小组
    public function getAll()
    {


        return $this->select();
    }
    //筛选小组
    public  function search($parm){

        return $this->where($parm)->select();
    }
}