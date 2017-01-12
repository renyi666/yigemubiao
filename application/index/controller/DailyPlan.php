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
 * @created_time：2016/12/30 16:51
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


use think\Request;

class DailyPlan extends Base
{
    /**
     * 增加日报
     */
    public function addPlan()
    {
        $request = Request::instance();
        $list = $request->param();
        $dailyPlanM = new  \app\index\model\DailyPlan();
        $list['start_time'] = $list['time1'] . ":" . $list['time2'];
        $list['end_time'] = $list['time3'] . ":" . $list['time4'];
        $list['start_time'] = strtotime($list['start_time']);
        $list['end_time'] = strtotime($list['end_time']);
        unset($list['time1']);
        unset($list['time2']);
        unset($list['time3']);
        unset($list['time4']);
        $result = $dailyPlanM->addPlan($list);
        if ($result == 0) {

            return $this->showError(-120);
        }
        return $this->successReturn();
    }
    public  function  deletePlan(){

        $where['id']    =   input('id');
        $dailyPlanM  = new   \app\index\model\DailyPlan();
        $result =   $dailyPlanM->deletePlan($where);
        return $result;
    }
    public  function editPlan(){
        $request         =  Request::instance();
        $list   =   $request->param();
        $list['start_time'] = $list['time1'] . ":" . $list['time2'];
        $list['end_time'] = $list['time3'] . ":" . $list['time4'];
        $list['start_time'] = strtotime($list['start_time']);
        $list['end_time'] = strtotime($list['end_time']);
        unset($list['time1']);
        unset($list['time2']);
        unset($list['time3']);
        unset($list['time4']);
        $dailyPlanM =   new  \app\index\model\DailyPlan();
        $result =   $dailyPlanM->editPlan($list);
        return $result;
    }

}