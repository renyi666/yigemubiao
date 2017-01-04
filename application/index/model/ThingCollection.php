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


use think\Model;

class ThingCollection extends Model
{
    protected $autoWriteTimestamp = true;

    //收藏或者取消收藏
    public function collection($parm)
    {
        $parm['user_id'] = session('uid');
        $result = $this->where($parm)->find();

        //判断是否表中存在.假如存在，则修改收藏状态，不存在，则直接添加为收藏
        if ($result != null) {

            if ($result['status'] == 1) {

                $list['status'] = 2;
                $result = $this->where($parm)->update($list);
                if ($result <= 0) {

                    return -116;
                }

            } else {


                $list['status'] = 1;
                $result = $this->where($parm)->update($list);
                if ($result <= 0) {

                    return -116;
                }

            }


        } else {
            unset($result);

            $result = $this->save($parm);
            if ($result <= 0) {

                return -115;
            }

        }
        return $result;

    }

    //获得用户的收藏列表id
    public function getCollection($parm)
    {

        return $this->field('thing_id')->where($parm)->select();

    }

    public function getCollectionThing($parm)
    {

        $where['c.group_id'] = $parm['group_id'];
        $where['c.user_id'] = $parm['user_id'];
        $where['c.status'] = 1;
        $result = $this
            ->alias('c')
            ->where($where)
            ->field('t.id,t.name,t.user_id,t.group_id')
            ->join('thing t', 't.id=c.thing_id')
            ->select();
        return $result;

    }


}