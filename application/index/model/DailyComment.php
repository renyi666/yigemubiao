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
 * @created_time：2017/1/4 14:58
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\model;


use think\Model;

class DailyComment extends  Model
{
    protected $autoWriteTimestamp = true;

    /**添加评论
     * @param $parm
     * @return false|int
     */
    public  function addComment($parm){

        return $this->save($parm);
    }

    /**根据条件查找评论
     * @param $parm
     * @return false|\PDOStatement|string|\think\Collection
     */
    public  function  getAllByDailyPlanId($parm){


        return $this->where($parm)->select();
    }

}