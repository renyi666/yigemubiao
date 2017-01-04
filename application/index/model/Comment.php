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
 * @modified_time：2016/12/7 16:58
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\model;


use think\Model;

class Comment extends  Model
{
    protected $autoWriteTimestamp = true;


    //筛选某条时间下边的评论
    public  function getByThingLogId($parm){

        return $this->where($parm)->select();
    }
}