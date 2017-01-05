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
 * @created_time：2017/1/5 11:19
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


use app\index\model\DailyComment;

class Check extends Base
{

    public function index()
    {
        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $dailyCommentM = new DailyComment();

        //待审核的数目
        $where['group_id'] = input('group_id');
        $where['status'] = 0;
        $dailyCommentResult = $dailyCommentM->getAllByDailyPlanId($where);

        //审核未通过的数目
        unset($where['status']);
        $where['status'] = 2;
        $dailyCommentResultFail = $dailyCommentM->getAllByDailyPlanId($where);
        $dailyCommentResultNumber   =   count($dailyCommentResult);
        $dailyCommentResultFailNumber   =   count($dailyCommentResultFail);
        $this->assign('dailyCommentResultNumber',$dailyCommentResultNumber);
        $this->assign('dailyCommentResultFailNumber',$dailyCommentResultFailNumber);

        $this->assign('dailyCommentResult', $dailyCommentResult);
        $this->assign('dailyCommentResultFail', $dailyCommentResultFail);

        return $this->fetch();
    }

}