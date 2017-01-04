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
 * @created_time：2016/12/12 14:06
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\model;


use think\Model;

class DailyPlan extends Model
{

    protected $autoWriteTimestamp = true;

    /**查找plan
     * @param $parm
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getPlanByDailyId($parm)
    {
        $parm['type'] = 1;
        return $this->where($parm)->order('sort desc')->select();

    }

    public function getWorkByDailyId($parm)
    {

        $parm['type'] = 2;
        return $this->where($parm)->order('sort desc')->select();
    }

    /**增加
     * @param $parm
     * @return false|int
     */
    public  function  addPlan($parm){


        return $this->save($parm);
    }

    /**删除
     * @param $parm
     * @return int
     */
    public  function deletePlan($parm){

        return $this->where($parm)->delete();
    }
    public  function  findOne($parm){


        return $this->where($parm)->find();

    }

    public  function editPlan($parm){

        return $this->update($parm);
    }

}