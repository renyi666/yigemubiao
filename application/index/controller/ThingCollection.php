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
 * @modified_time：2016/11/30 15:25
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


use think\Controller;
use think\Request;

class ThingCollection extends  Controller
{
    //收藏或者取消收藏
    public  function  thingCollection(){
        $data   =   Request::instance();
        $list   =$data->param();
        $thingCollectionM   =new \app\index\model\ThingCollection();

        $result =   $thingCollectionM->collection($list);

        return $result;


    }


}