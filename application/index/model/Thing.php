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
use think\Log;
use think\Model;

class Thing extends Model
{
    protected $autoWriteTimestamp = true;

    //z增加事件
    public function addThing($parm)
    {
        $parm['user_id'] = implode(',', $parm['user_id']);

        $result = $this->save($parm);
        $collection['thing_id'] = Db::name('thing')->getLastInsID();
        $thingColectionM = new ThingCollection();
        $collection['group_id'] = $parm['group_id'];
        $collectionData = explode(",", $parm['user_id']);
        Log::write($collectionData);


        foreach ($collectionData as $key => $value) {
            $collection['user_id'] = $value;
            $collection['create_time'] = time();
            $collection['update_time']  =time();

            $collection_result = $thingColectionM->insert($collection);

        }


        if ($result <= 0) {

            return -113;
        }

        return $result;
    }

    //查找小组所有事件
    public function getAll($parm)
    {

        $memberM    = new Member();
        $result = $this
            ->alias('t')
            ->where("t.group_id=" . $parm['group_id'])
            ->where('g.status=1')
            ->field('t.id,t.name,t.user_id,t.group_id')
            ->join('member m', 'm.id=t.user_id')
            ->join('group_member g', 'g.user_id=m.id')
            ->select();

        foreach ($result as $key =>$value){
            $where  =   $value['user_id'];
            $where =   explode(",",$where);

            $result[$key]['nickname']   = "";
            foreach ($where as $k =>$v){

                $list['id'] =   $v;
                $data   =   $memberM->getNameById($list);
                if($k==0){
                     $result[$key]['nickname']=$data;

                     }else{
                     $result[$key]['nickname']=$result[$key]['nickname'].",".$data;
                     }
            }




        }
        return $result;
    }

    //查找小组所有事件
    public function getAll1($parm)
    {
        $ceshi['t.name']=$parm['t.name'];
$memberM  =new Member();
        $result = $this
            ->alias('t')
            ->where("t.group_id=" . $parm['group_id'])
            ->where('g.status=1')
            ->where($ceshi)
            ->field('t.id,t.name,t.user_id,t.group_id')
            ->join('member m', 'm.id=t.user_id')

            ->join('group_member g', 'g.user_id=m.id')

            ->select();

        foreach ($result as $key =>$value){
            $where  =   $value['user_id'];
            $where =   explode(",",$where);

            $result[$key]['nickname']   = "";
            foreach ($where as $k =>$v){

                $list['id'] =   $v;
                $data   =   $memberM->getNameById($list);
                if($k==0){
                    $result[$key]['nickname']=$data;

                }else{
                    $result[$key]['nickname']=$result[$key]['nickname'].",".$data;
                }
            }




        }

        return $result;
    }


    public function getAll2($parm)
    {
        $memberM  =new  Member();
//        $where['t.user_id'] = $parm['user_id'];
        $where[]="FIND_IN_SET(".$parm['user_id'].", t.user_id)";

        $result = $this
            ->alias('t')
            ->where("t.group_id=" . $parm['group_id'])
           ->where("FIND_IN_SET(".$parm['user_id'].", t.user_id)")
            ->where('g.status=1')
            ->order('t.create_time desc')
            ->field('t.id,t.name,t.user_id,t.group_id')
            ->join('member m', 'm.id=t.user_id')
            ->join('group_member g', 'g.user_id=m.id')
            ->select();
        foreach ($result as $key =>$value){
            $where  =   $value['user_id'];
            $where =   explode(",",$where);

            $result[$key]['nickname']   = "";
            foreach ($where as $k =>$v){

                $list['id'] =   $v;
                $data   =   $memberM->getNameById($list);
                if($k==0){
                    $result[$key]['nickname']=$data;

                }else{
                    $result[$key]['nickname']=$result[$key]['nickname'].",".$data;
                }
            }




        }
        return $result;

    }

    public function getByID($parm)
    {

        return $this->where($parm)->find();
    }

    //更新
    public function editThing($parm)
    {
        $thingLogM = new   ThingLog();
        Db::startTrans();
        $thing_parm['id'] = $parm['thing_id'];
        $thing_parm['content'] = $parm['content'];

        $result = $this->update($thing_parm);

        if ($result <= 0) {
            Db::rollback();
            return -117;
        }

        $log_result = $thingLogM->save($parm);
        if ($log_result <= 0) {
            Db::rollback();
            return -117;
        }
        Db::commit();
        return $result;


    }

    public function updateTing($parm)
    {

        $collection['thing_id']= $where['id'] = $parm['id'];
        $thingResult = $this->where($where)->find();
        $thingResult1 = explode(",", $thingResult['user_id']);
//        Log::write($thingResult);
        $collection['group_id'] = $thingResult['group_id'];

        $thingColectionM = new ThingCollection();
        $collectionWhere['thing_id'] = $parm['id'];
        //消除原来收藏的
        foreach ($thingResult1 as $key => $value) {
            $collectionWhere['user_id'] = $value;
            $thingColectionM->where($collectionWhere)->delete();
        }


        $parm['user_id'] = implode(",", $parm['user_id']);
        $this->update($parm);
        $UserId = explode(",", $parm['user_id']);
        //添加新的收藏的
        foreach ($UserId AS $k => $v) {
            $collection['user_id'] = $v;
            $collection['create_time']  =   time();
            $collection['update_time']  =time();
            $thingColectionM->insert($collection);

        }

        return 1;
    }


}