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
 * @modified_time：2016/12/8 10:54
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


use app\index\model\DailyComment;
use app\index\model\DailyPlan;
use app\index\model\GroupMember;
use app\index\model\Member;
use app\index\model\ThingCollection;

class Daily extends Base
{

    /**日报默认界面
     * @return mixed
     */
    public function index()
    {
        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $dailyM = new \app\index\model\Daily();
        $dailyPlanM = new DailyPlan();
        /**
         * 获取明天的时间
         */
        $tommorrow = strtotime(date("Y-m-d"), time()) + 60 * 60 * 24;
        /*
         * 把明天和今天的时间赋值到模板
         */
        $tommorrowLimit = $tommorrow + 1;
        $this->assign('tommorrow', $tommorrowLimit);
        $today = time();
        $allToday = array(time(), time() + 60 * 60 * 24);
        $this->assign('today', $today);
        $allToday = implode(',', $allToday);
        $this->assign('allToday', $allToday);
        /**
         * 把明天的时间转换格式
         */
        $tommorrow = date('Y-m-d', $tommorrow);
        /*
         * 把相应的时间截取出来
         */
        $tommorrowData['year'] = substr($tommorrow, 0, 4);
        $tommorrowData['month'] = substr($tommorrow, 5, 2);
        $tommorrowData['day'] = substr($tommorrow, 8, 2);
        /*
         * 把时间赋值到模板
         */
        $this->assign('tommorrowData', $tommorrowData);
        $parm['group_id'] = input('group_id');
        $parm1['id'] = input('group_id');
        /*
         * 筛选每天对应的工作计划和实际工作
         */
        //判断是否有组员筛选条件
        $receiveData['userId'] = input('userId');
        if (isset($receiveData['userId']) && $receiveData['userId'] != null && $receiveData['userId'] != "") {
            $dailyWhere['user_id'] = $receiveData['userId'];
        } else {
            $dailyWhere['user_id'] = $userInfo['id'];
        }
        $this->assign('nowUserId', $dailyWhere['user_id']);
        $dailyWhere['group_id'] = input('group_id');
        $dailyResult = $dailyM->getAll($dailyWhere);
        //从dailyplan表里查出工作计划和实际工作
        foreach ($dailyResult as $key => $value) {
            $dailyPlanWhere['daily_id'] = $value['id'];
            $dailyResult[$key]['plan'] = $dailyPlanM->getPlanByDailyId($dailyPlanWhere);
            $dailyResult[$key]['work'] = $dailyPlanM->getWorkByDailyId($dailyPlanWhere);
            $timeDifference = time() - $value['limit_time'];
            /*
             * 求出时间差。判断是否是当天该有的操作
             */
            if ($timeDifference > 0 && $timeDifference <= 60 * 60 * 24) {
                $dailyResult[$key]['timeDifference'] = 1;
            } else {
                $dailyResult[$key]['timeDifference'] = 2;
            }
        }
        if (input('time1') != null && input('time2') != null) {
            unset($receiveData);
            $ReceiveData['time1'] = input('time1');
            $ReceiveData['time2'] = input('time2');
            $receiveData['time1'] = strtotime(input('time1'));
            $receiveData['time2'] = strtotime(input('time2') . "-30");
            $receiveData['time2'] = strtotime(date('Y-m-d', strtotime(date('Y-m-01', strtotime(input('time2'))) . ' +1 month -1 day'))) + 60 * 60 * 24;
            foreach ($dailyResult as $k => $v) {
                if ($v['limit_time'] > $receiveData['time2'] || $v['limit_time'] < $receiveData['time1']) {
                    unset($dailyResult[$k]);
                }
            }
        } else {
            $ReceiveData['time1'] = 0;
            $ReceiveData['time2'] = 0;
        }
        $this->assign('ReceiveData', $ReceiveData);
        $this->assign('dailyResult', $dailyResult);
        $groupInfo = $this->getGroupInfo($parm1);
        //把小组id赋值到模板
        $this->assign('groupInfo', $groupInfo);
        $groupmmeberM = new GroupMember();
        $memberM = new Member();
        //保证是在职
        $parm['status'] = 1;
        $groupUserInfo = $groupmmeberM->getUser($parm);
        foreach ($groupUserInfo as $key => $value) {
            $parm2['id'] = $value['user_id'];
            $result = $memberM->getUser($parm2);
            $groupUserInfo[$key]['user_name'] = $result['nickname'];
        }
        $this->assign('groupUserInfo', $groupUserInfo);
        return $this->fetch();
    }

    //增加空计划
    public function add_plan_empty()
    {
        $parm['group_id'] = input('group_id');
        $where['time'] = input('year') . "-" . input('month') . "-" . input('day');
        $where['time'] = strtotime($where['time']);
        $parm['limit_time'] = $where['time'] + 1;//当日凌晨零点  0:0:1
        $parm['user_id'] = session('userInfo.id');
        $dailyM = new \app\index\model\Daily();
        /**
         * 添加周几
         */
        $parm['week'] = date("N", $parm['limit_time']);
        if ($parm['week'] == "1") {
            $parm['week'] = "一";
        } elseif ($parm['week'] == "2") {
            $parm['week'] = "二";
        } elseif ($parm['week'] == "3") {
            $parm['week'] = "三";
        } elseif ($parm['week'] == "4") {
            $parm['week'] = "四";
        } elseif ($parm['week'] == "5") {
            $parm['week'] = "五";
        } elseif ($parm['week'] == "6") {
            $parm['week'] = "六";
        } elseif ($parm['week'] == "7") {
            $parm['week'] = "日";
        }

        $result = $dailyM->add_plan_empty($parm);
        if ($result <= 0) {
            return $this->showError($result);
        }
        return $result;
    }

    /**增加页面
     * @return mixed
     */
    public function add()
    {
        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $where['group_id'] = input('group_id');
        $list['daily_id'] = input('id');
        $where['user_id'] = $userInfo['id'];
        $list['type'] = input('type');
        $thingCollectionM = new  ThingCollection();
        $thingCoResult = $thingCollectionM->getCollectionThing($where);
        $this->assign('thingCoRes', $thingCoResult);
        $this->assign('DailyId', $list['daily_id']);
        $this->assign('Type', $list['type']);
        return $this->fetch();
    }

    /**编辑
     * @return mixed
     */
    public function edit()
    {
        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $dailyPlanM = new   DailyPlan();

        $where['id'] = input('id');
        $result = $dailyPlanM->findOne($where);
        if ($result == null || $result == "") {
            redirect("Baocuo/index");
        }
        unset($where);
        $where['group_id'] = input('group_id');
        $list['daily_id'] = input('id');
        $where['user_id'] = $userInfo['id'];
        $thingCollectionM = new  ThingCollection();
        $thingCoResult = $thingCollectionM->getCollectionThing($where);
        $result['start_time'] = date('H:i:s', $result['start_time']);
        $result['end_time'] = date('H:i:s', $result['end_time']);
        $result['time1'] = substr($result['start_time'], 0, 2);
        $result['time2'] = substr($result['start_time'], 3, 2);
        $result['time3'] = substr($result['end_time'], 0, 2);
        $result['time4'] = substr($result['end_time'], 3, 2);
        $this->assign('thingCoRes', $thingCoResult);
        $this->assign('result', $result);
        return $this->fetch();
    }

    /**评论页面
     * @return mixed
     */
    public function comment()
    {
        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $dailyPlan['id'] = input('dailyplanid');
        $dailyPlanM = new  DailyPlan();
        $dailyCommentM = new  DailyComment();
        $dailyPlanResult = $dailyPlanM->findOne($dailyPlan);
        if ($dailyPlanResult == null || $dailyPlanResult == "") {
            $this->redirect('Baocuo/index');
        }
        $dailyCommentWhere['dailyplan_id'] = input('dailyplanid');
        $dailyCommentResult = $dailyCommentM->getAllByDailyPlanId($dailyCommentWhere);
        //统计出有几条评论，几个差评
        $commentData['number'] = count($dailyCommentResult);//总共有几个评论
        $count = 0;
        for ($i = 0; $i < $commentData['number']; $i++) {
            if ($dailyCommentResult[$i]['type'] == 1) {
                $count += 1;
            }
        }
        $commentData['count'] = $count;
        $this->assign('commentData', $commentData);//评论的数目和差评的数目
        $this->assign('dailyCommentResult', $dailyCommentResult);
        $this->assign('dailyPlanResult', $dailyPlanResult);
        return $this->fetch();
    }
}