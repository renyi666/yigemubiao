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
 * @modified_time：2016/11/30 14:24
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\model;


use think\Db;
use think\Model;

class Thing extends  Model
{
    protected $autoWriteTimestamp = true;

    //z增加事件
    public  function addThing($parm){

        $result = $this->save($parm);
        $collection['thing_id'] = Db::name('thing')->getLastInsID();
        $thingColectionM    =   new ThingCollection();
        $collection['group_id'] =   $parm['group_id'];
        $collection['user_id']  =   session('uid');
        $collection_result  =   $thingColectionM->save($collection);




       if($result<=0){

           return -113;
       }

       return $result;
    }

    //查找小组所有事件
    public  function getAll($parm){

        $result=$this
            ->alias('t')
            ->where("t.group_id=".$parm['group_id'])
            ->where('g.status=1')
            ->field('t.id,t.name,t.user_id,t.group_id,m.nickname')
            ->join('member m', 'm.id=t.user_id')
            ->join('group_member g','g.user_id=m.id')
            ->select();
        return $result;
    }

    public  function getAll1($parm){
        $where['t.name']=$parm['name'];
            $result=$this
            ->alias('t')
            ->where("t.group_id=".$parm['group_id'])
            ->where($where)
            ->where('g.status=1')
            ->field('t.id,t.name,t.user_id,t.group_id,m.nickname')

            ->join('member m', 'm.id=t.user_id')
            ->join('group_member g','g.user_id=m.id')
            ->select();
        return $result;

    }

    public  function getAll2($parm){
        $where['t.user_id']=$parm['user_id'];
        $result=$this
            ->alias('t')
            ->where("t.group_id=".$parm['group_id'])
            ->where($where)
            ->where('g.status=1')
            ->field('t.id,t.name,t.user_id,t.group_id,m.nickname')
            ->join('member m', 'm.id=t.user_id')
            ->join('group_member g','g.user_id=m.id')
            ->select();

        return $result;

    }
    public  function getByID($parm){

        return $this->where($parm)->find();
    }
    //更新
    public  function editThing($parm){
        $thingLogM  =   new   ThingLog();
        Db::startTrans();
        $thing_parm['id']   =   $parm['thing_id'];
        $thing_parm['content']  =   $parm['content'];

        $result =   $this->update($thing_parm);

        if($result<=0){
            Db::rollback();
            return -117;
        }

        $log_result     =   $thingLogM->save($parm);
        if($log_result<=0){
            Db::rollback();
            return -117;
        }
        Db::commit();
        return $result;


    }
    public function updateTing($parm){

        return $this->update($parm);
    }




}