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
 * @created_time：2017/1/4 14:57
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


use think\Request;

class DailyComment extends Base
{

    /**添加评论
     * @return false|int
     */
    public  function addComment(){

        $dailyCommentM  = new  \app\index\model\DailyComment();
        $request   =  Request::instance();
        $list   =$request->param();
        $result =   $dailyCommentM->addComment($list);
        return $result;


    }


}