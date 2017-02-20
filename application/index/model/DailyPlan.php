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


use think\Log;
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

        $dailyComment = new DailyComment();
        $parm['type'] = 1;

//        $result = $this->where($parm)->order('sort asc')->select();
        $result = $this
            ->alias('d')
            ->where($parm)
            ->field('d.id,d.daily_id,d.content,d.name,d.create_time,d.update_time,d.type,d.start_time,d.end_time,d.sort,d.thing_id,t.name thing_name')
            ->join('thing t','d.thing_id=t.id')
            ->select();
        //统计评论数目和差评数目
        foreach ($result as  $key =>$value){
            $where['dailyplan_id']  =   $value['id'];
            $result[$key]['comment']    =   $dailyComment->getAllByDailyPlanId($where);
            $result[$key]['comment_number'] =   count($result[$key]['comment']);
            $result[$key]['comment_count']=0;
            foreach ($result[$key]['comment'] as $k =>$v){

                if($v['type']==1){

                    $result[$key]['comment_count']+=1;
                }
            }

            if($value['start_time']!=null&&$value['end_time']!=null){
                $result[$key]['start_time'] =   date('H:i',$value['start_time']);
                $result[$key]['end_time'] =   date('H:i',$value['end_time']);
            }else{

                $result[$key]['start_time'] =  0;
                $result[$key]['end_time'] =  0;
            }


        }
        return $result;

    }

    public function getWorkByDailyId($parm)
    {
        $dailyComment = new DailyComment();

        $parm['type'] = 2;
        $result = $this
            ->alias('d')
            ->where($parm)
            ->field('d.id,d.daily_id,d.content,d.name,d.create_time,d.update_time,d.type,d.start_time,d.end_time,d.sort,d.thing_id,t.name thing_name')
            ->join('thing t','t.id=d.thing_id')
            ->select();
//        $result =$this->where($parm)->order('sort asc')->select();

        //统计评论数目和差评数目

        foreach ($result as  $key =>$value){
            $where['dailyplan_id']  =   $value['id'];
            $result[$key]['comment']    =   $dailyComment->getAllByDailyPlanId($where);


            $result[$key]['comment_number'] =   count($result[$key]['comment']);
            $result[$key]['comment_count']=0;
            foreach ($result[$key]['comment'] as $k =>$v){

                if($v['type']==1){

                    $result[$key]['comment_count']+=1;
                }
            }

           $timedifference    =  abs($value['start_time'] -$value['end_time']);
            $result[$key]['start_time'] =   date('H:i',$value['start_time']);
            $result[$key]['end_time'] =   date('H:i',$value['end_time']);

            $hour  = floor($timedifference/3600);
            $second =   $timedifference%3600;
            $minute = floor($second/60);
            if($hour==0){

                $result[$key]['yongshi']    =   $minute."分";
            }elseif($minute==0){
                $result[$key]['yongshi']    =  $hour."时";

            }else{

                $result[$key]['yongshi']    =  $hour."时".$minute."分";

            }






        }
        return $result;
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