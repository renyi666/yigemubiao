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

        $userCommentM   =  new  UserComment();

        $this->save($parm);
        $list['comment_id'] =   $this->id;
        $userInfo   =session('userInfo');
        $list['user_id']    =$userInfo['id'];
        $list['group_id']   =$parm['group_id'];

        return $userCommentM->addComment($list);
    }

    /**根据条件查找评论
     * @param $parm
     * @return false|\PDOStatement|string|\think\Collection
     */
    public  function  getAllByDailyPlanId($parm){


        return $this->where($parm)->order('create_time desc')->select();
    }

    /**编辑单个通过情况
     * @param $parm
     * @return $this
     */
    public  function editComment($parm){


        return $this->update($parm);
    }

    /**全部通过或者是不通过
     * @param $parm
     * @return int|string
     */
    public  function allEdit($parm){

        $where['status']    =   1;
        return $this->where($parm)->update($where);

    }



}