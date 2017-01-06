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
 * @modified_time：2016/11/27 9:22
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */
namespace app\index\model;


use think\Model;

class Member extends Model
{
    protected $autoWriteTimestamp = true;

    //登录功能
    public function login($parm)
    {//parm传递参数应包括手机号和密码

        $where['mobile'] = $parm['mobile'];

        $result = $this->where($where)->find();

        //用户不存在
        if ($result == null || $result == "") {

            return '-100';
        }

        $list['password'] = md5($parm['password']);

        if ($list['password'] != $result['password']) {

            return '-101';
        }

        session('userInfo',$result);
        session('uid', $result['id']);

        return 1;
    }

    //修改资料
    public function editUser($parm)
    {//parm参数应该含有id       手机号 name 和密码都可以为空


        $where['id'] = $parm['id'];
        $result = $this->where($where)->find();
        if ($result == null) {

            return -100;
        }
        if ($parm['password'] != null && $parm['password'] != '') {

            $parm['password'] = md5($parm['password']);
        }

        //过滤数组为空的值
        $parm = array_filter($parm);


        $result = $this->update($parm);
        if($result==0){

            return -111;
        }

        return $result;

    }

    public function getUser($where)
    {

        return $this->where($where)->find();

    }
    public  function getGroupUser($parm){

      return $this->where($parm)->select();
    }

    /**返回用户的名字
     * @param $parm
     * @return mixed
     */
    public  function getNameById($parm){

        return $this->where($parm)->value('nickname');
    }
}