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
 * @created_time：2017/1/5 11:56
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\model;


use think\Model;

class UserComment extends Model
{

    protected $autoWriteTimestamp = true;


    public function addComment($parm)
    {

        return $this->save($parm);
    }

    /**根据条件修改状态
     * @param $parm
     * @return int|string
     */
    public function editUserComment($parm)
    {

        $where['status'] = $parm['check'];
        unset($parm['check']);
        $where['update_time'] = time();
        return $this->where($parm)->update($where);

    }

    /**全部tongguo
     * @param $parm
     * @return int|string
     */
    public  function allEdit($parm){

        $list['status'] =1;
        $list['update_time']    =   time();
        return $this->where($parm)->update($list);


    }
    public  function getByUser($parm){

        return $this->where($parm)->select();
    }


}