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
 * @modified_time：2016/12/7 13:50
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\model;


use think\Model;

class ThingLog extends  Model
{

    protected $autoWriteTimestamp = true;


    public function insertData($parm){

        return $this->save($parm);
    }
    public  function getByThingId($parm){

        return $this->where($parm)->select();
    }

}