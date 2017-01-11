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

namespace app\index\model;


use think\Db;
use think\Model;

class Daily extends  Model
{
    protected $autoWriteTimestamp = true;

    //新建日期计划
    public  function add_plan_empty($parm){

        Db::startTrans();
        $where['limit_time']    =   $parm['limit_time'];
        $return_result  =   $this->where($where)->find();
        if($return_result!=null){

                return -119;
        }
        $result =$this->save($parm);

        if($result!=1){
            Db::rollback();
            return -118;

        }
//        unset($where);
//        $where['daily_id']  =   $this->getLastInsID();
//        //新建完成后，在work表和plan表里也添加相应的空白数据

        Db::commit();

        return $result;

    }

    /**根据条件查找所有日报
     * @param $parm
     * @return false|\PDOStatement|string|\think\Collection
     */
    public  function getAll($parm){

        return $this->where($parm)->order('limit_time  desc')->select();
    }

    /**查找单条记录,返回user_id
     * @param $parm
     * @return array|false|\PDOStatement|string|Model
     */
    public   function getOne($parm){

        return $this->where($parm)->find();
    }

}