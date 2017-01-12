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
 * @created_time：2017/1/9 10:42
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


use app\index\model\DailyComment;
use app\index\model\GroupMember;
use app\index\model\Member;
use think\Request;

class Target extends Base
{


    public function index()
    {
        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $targetM = new  \app\index\model\Target();
        $dailyCommentM = new  DailyComment();
        $groupmmeberM = new   GroupMember();
        $memberM = new Member();

        $targetWhere['group_id'] = $parm['group_id'] = input('group_id');
        $parm['status'] = 1;
        $groupUserInfo = $groupmmeberM->getUser($parm);
        //获取小组内成员信息
        foreach ($groupUserInfo as $key => $value) {

            $parm2['id'] = $value['user_id'];
            $result = $memberM->getUser($parm2);
            $groupUserInfo[$key]['user_name'] = $result['nickname'];
        }


        //获得传递过来的用户id
        $receiveData['userId'] = input('userId');


        if (isset($receiveData['userId']) && $receiveData['userId'] != null && $receiveData['userId'] != "") {
            $targetWhere['user_id'] = $receiveData['userId'];

        } else {

            $targetWhere['user_id'] = $userInfo['id'];
        }
        //获得传递过来的月份
        $receiveData['target_time'] = input('target_time');
        if (isset($receiveData['target_time']) && $receiveData['target_time'] != null) {
            $targetWhere['target_time'] = $receiveData['target_time'];


        } else {
            $targetWhere['target_time'] = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), 1, date("Y"))));

        }


        //把前台传递过来的时间转换
        $panduan    =   date('Y-m',$targetWhere['target_time']);
        $selectTime['1']    =   substr($panduan,0,4);
        $selectTime['2']    =   substr($panduan,6,2)*1;

        $this->assign('selectTime',$selectTime);


        //查询一个目标结果
        $targetResult = $targetM->getOne($targetWhere);

        //如果为空，就自动添加一个,吧添加后的id赋值到模板
        if ($targetResult == null) {

            $targetId = $targetM->addTarget($targetWhere);

        } else {
            $targetId = $targetResult['id'];
        }
        $this->assign('targetId', $targetId);

        //查询一个目标结果,在查询一次
        $targetResult = $targetM->getOne($targetWhere);




        $endTime = strtotime(date('Y-m-d', strtotime(date('Y-m-01', time()) . ' +1 month -1 day')));//截止到当前月份的最后一天
        //判断前台页面是否该显示编辑按钮

        if ($targetResult['target_time'] > $endTime) {

            $targetResult['bianji'] = 1;
        } else {
            $targetResult['bianji'] = 0;


        }

        $this->assign('tergetResult', $targetResult);


        //根据目标获得相应的评论数目
        $dailyCommentWhere['target_id'] = $targetResult['id'];
        $targetCommentResult = $dailyCommentM->getAllByDailyPlanId($dailyCommentWhere);


        //判断差评有几条
        if ($targetCommentResult != null) {
            foreach ($targetCommentResult as $k1 => $v1) {
                $targetCommentResultNumber['1'] = 0;
                if ($v1['type'] == 1) {
                    $targetCommentResultNumber['1'] += 1;
                }
            }
            //总共有几条差评
            $targetCommentResultNumber['0'] = count($targetCommentResult);
        } else {

            $targetCommentResultNumber['1'] = 0;
            $targetCommentResultNumber['0'] = 0;

        }


        $this->assign('targetCommentResultNumber', $targetCommentResultNumber);
        $this->assign('nowUserId', $targetWhere['user_id']);
        $this->assign('groupUserInfo', $groupUserInfo);

        //获取前后四个月的时间
        $targetMonth['0']['month1'] = $beforeLastMonth = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m",$targetWhere['target_time']) - 2, 1, date("Y",$targetWhere['target_time'])));

        $targetMonth['1']['month1'] = $lastMonth = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m",$targetWhere['target_time']) - 1, 1, date("Y",$targetWhere['target_time'])));

        $targetMonth['2']['month1'] = $nowMonth = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m",$targetWhere['target_time']), 1, date("Y",$targetWhere['target_time'])));
        $targetMonth['3']['month1'] = $nextMonth = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m",$targetWhere['target_time']) + 1, 1, date("Y",$targetWhere['target_time'])));
        $targetMonth['4']['month1'] = $afterNextMonth = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m",$targetWhere['target_time']) + 2, 1, date("Y",$targetWhere['target_time'])));
        foreach ($targetMonth as $k => $v) {
            $targetMonth[$k]['month'] = substr($v['month1'], 5, 2) * 1;//乘1可以去除前边的零
            $targetMonth[$k]['target_time'] = strtotime($v['month1']);
        }


        $this->assign('targetMonth', $targetMonth);


        return $this->fetch();
    }

    public function editTarget()
    {

        $request = Request::instance();
        $list = $request->param();

        $targetM = new    \app\index\model\Target();
        $result = $targetM->editTarget($list);

        return $result;


    }

    public function comment()
    {
        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $targetWhere['group_id'] = input('group_id');
        $targetWhere['target_id'] = input('target_id');
        $this->assign('targetWhere', $targetWhere);
        if ($targetWhere['target_id'] == null || !isset($targetWhere['target_id'])) {


            $this->redirect('Baocuo/index');
        }


        $dailyCommentM = new DailyComment();

        $dailyCommentResult = $dailyCommentM->getAllByDailyPlanId($targetWhere);

        $dailyCommentNumber['0'] = 0;
        $dailyCommentNumber['1'] = 0;
        if ($dailyCommentResult != null) {


            foreach ($dailyCommentResult as $key => $value) {

                if ($value['type'] == 1) {

                    $dailyCommentNumber['1'] += 1;

                }

            }
            $dailyCommentNumber['0'] = count($dailyCommentResult);
        }

        $this->assign('dailyCommentNumber', $dailyCommentNumber);
        $this->assign('dailyCommentResult', $dailyCommentResult);

        return $this->fetch();
    }

}