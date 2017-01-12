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
 * @modified_time：2016/12/7 16:55
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


class Comment extends  Base
{

    public  function index(){
        $commentM   =   new \app\index\model\Comment();
        //获取小组信息
        $list['id'] = input('group_id');
        $groupInfo = $this->getGroupInfo($list);
        $this->assign('groupInfo', $groupInfo);
        //个人信息
        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $parm['thing_log_id']   =   input('thing_log_id');
        $comment_result =   $commentM->getByThingLogId($parm);
        return $this->fetch();
    }
}