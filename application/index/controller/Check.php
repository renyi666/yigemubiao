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
 * @created_time：2017/1/5 11:19
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


use app\index\model\DailyComment;
use app\index\model\UserComment;

class Check extends Base
{

    public function index()
    {


        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $dailyCommentM = new DailyComment();

        //待审核的数目
        $where['group_id'] = input('group_id');
        $where['status'] = 0;
        $dailyCommentResult = $dailyCommentM->getAllByDailyPlanId($where);

        //审核未通过的数目
        unset($where['status']);
        $where['status'] = 2;
        $dailyCommentResultFail = $dailyCommentM->getAllByDailyPlanId($where);
        $dailyCommentResultNumber   =   count($dailyCommentResult);
        $dailyCommentResultFailNumber   =   count($dailyCommentResultFail);
        $this->assign('dailyCommentResultNumber',$dailyCommentResultNumber);
        $this->assign('dailyCommentResultFailNumber',$dailyCommentResultFailNumber);

        $this->assign('dailyCommentResult', $dailyCommentResult);
        $this->assign('dailyCommentResultFail', $dailyCommentResultFail);

        return $this->fetch();
    }

    public  function checkComment(){
        $userInfo = session('userInfo');
        $url    =   $_SERVER['HTTP_REFERER'];
        $dailyCommentM  =  new  DailyComment();

        $userCommentM   =  new  UserComment();
        $receiveData['group_id']    =   input('group_id');
        //判断点击了全部通过
        if(isset($receiveData['group_id'])&&is_numeric($receiveData['group_id'])){



            $receiveData['status']=0;

            $UserCommentResult  =$userCommentM->allEdit($receiveData);
            $result =   $dailyCommentM->allEdit($receiveData);



        }else{
            //没有点击全部通过

            $list['id'] =   input('id');
            $list['status'] =   input('check');
            if(!isset($list['id'])||!isset($list['status'] )){


                $this->redirect('Baocuo/index');
            }

            $result = $dailyCommentM->editComment($list);


            $where['comment_id']    =   input('id');
            $where['user_id']   =   $userInfo['id'];
            $where['check']=input('check');

            $UserCommentResult  =   $userCommentM->editUserComment($where);


        }


        $this->redirect($url);




    }

}