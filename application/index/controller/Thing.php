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
 * @modified_time：2016/11/30 13:40
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


use app\index\model\DailyComment;
use app\index\model\DailyPlan;
use app\index\model\Group;
use app\index\model\GroupMember;
use app\index\model\Member;
use app\index\model\ThingCollection;
use app\index\model\ThingLog;
use think\Request;

class Thing extends Base
{

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub


    }

    public function index()
    {

        $this->isLogin();
        $thing = new \app\index\model\Thing();
        $groupMemberM = new GroupMember();
        $memberM = new Member();
        $thingCollectionM = new ThingCollection();
        $list['name'] = input('name', 0);
        $list1['id'] = input('id', 0);
        $where1['id'] = input('group_id');
        $groupInfo = $this->getGroupInfo($where1);

        //把小组id赋值到模板
        $this->assign('groupInfo', $groupInfo);
        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);

        //下拉框选择不为空
        if ($list1['id'] != "0") {


            $parm['group_id'] = $groupInfo['id'];

            $parm['user_id'] = $list1['id'];
            $this->assign('selectUserID', $parm['user_id']);
            $result = $thing->getAll2($parm);

        } else if ($list['name'] != "0") {//搜索框不为空


            $parm['group_id'] = $groupInfo['id'];
            $parm['name'] = array('like', '%' . $list['name'] . '%');
            $this->assign('selectUserID', 0);
            $result = $thing->getAll1($parm);

        } else {
            //为空的情况


            $this->assign('selectUserID', 0);
            $parm['group_id'] = $groupInfo['id'];
            $result = $thing->getAll($parm);

        }

//判断result是否有结果，若有结果则操作
        if ($result != null) {

            foreach ($result as $key1 => $v) {

                $array[] = $v['id'];

            }
            $array = array_unique($array);
            foreach ($array as $key3 => $v3) {
                foreach ($result as $key4 => $v4) {
                    if ($v3 == $v4['id']) {
                        $data2[$key3] = $result[$key4];
                    }
                }
            }
            unset($result);
            $result = $data2;

        }


        $parm1['group_id'] = $groupInfo['id'];
        $parm1['status'] = 1;

        $result1 = $groupMemberM->getAllUser($parm1);
        foreach ($result1 as $k => $v) {

            $data1[$k] = $v['user_id'];
        }


        //判断用户收藏的列表中是否有该事件的id
        $collection_where['user_id'] = $userInfo['id'];
        $collection_where['status'] = 1;
        $collection_result = $thingCollectionM->getCollection($collection_where);

        //把collection_result转换成以逗号形式表示的
        foreach ($collection_result as $key => $value) {


            $collection_result1[$key] = $value['thing_id'];

        }


        //给collection_result增加一个字段判断是否收藏
        foreach ($result as $key1 => $value1) {

            if (in_array($value1['id'], $collection_result1)) {

                $result[$key1]['shoucang'] = 1;

            } else {
                $result[$key1]['shoucang'] = 2;


            }


        }


        $data = array_unique($data1);
        $data = implode(',', $data);

        $where['id'] = array('in', $data);
        $select_result = $memberM->getGroupUser($where);
        $this->assign('select_result', $select_result);


        $this->assign('result', $result);

        $this->assign('groupInfo', $groupInfo);


        return $this->fetch();

    }

    public function add()
    {

        $groupmemberM = new GroupMember();
        $groupM = new Group();
        $memberM = new  Member();
        $thingM = new \app\index\model\Thing();
        //小组的信息
        $request = Request::instance();
        $list = $request->param();
        if (isset($list['thing_id'])) {

            $panduan = 1;

            $thing_where['id'] = $list['thing_id'];

            $thing_result = $thingM->getByID($thing_where);

            $this->assign('thing_result', $thing_result);


        } else {

            $panduan = 0;

        }
        $this->assign('panduan', $panduan);
        $grup_where['id'] = input('group_id');
        $groupInfo = $this->getGroupInfo($grup_where);
        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $this->assign('groupInfo', $groupInfo);
        //查找小组内用户
        $whre['user_id'] = $userInfo['id'];
        $where['group_id'] = $groupInfo['id'];
        $where['status'] = 1;
        //从groupmember表里查出的小组在职用户id
        $result = $groupmemberM->getAllUser($where);

        foreach ($result as $key => $value) {

            $data[$key] = $value['user_id'];
        }


        $data = implode(',', $data);
        $member_where['id'] = array('in', $data);

        //查询出来用户表里用户的具体信息
        $member_result = $memberM->getGroupUser($member_where);

        $this->assign('member_result', $member_result);

        return $this->fetch();
    }

    public function detail()
    {

        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $memberM = new Member();
        $thingM = new  \app\index\model\Thing();
        $thingLogM = new ThingLog();
        //获取小组信息
        $list['id'] = input('group_id');
        $groupInfo = $this->getGroupInfo($list);
        $this->assign('groupInfo', $groupInfo);
        //获取事件详情
        $parm['id'] = input('thing_id');
        $result = $thingM->getByID($parm);
        //获取用户信息
        $parm1['id'] = $result['user_id'];

        $user_result = $memberM->getUser($parm1);
        $result['user_name'] = $user_result['nickname'];
        //判断是否三十天内更新
        $condition = $result['update_time'] * 60 * 60 * 24 * 30;
        if ($condition < time()) {

            $result['condition'] = 1;

        } else {

            $result['condition'] = 0;
        }
        $this->assign('result', $result);

        //事件更新记录
        $parm_log['thing_id'] = input('thing_id');
        $log_result = $thingLogM->getByThingId($parm_log);
        foreach ($log_result as $k => $v) {
            $where['id'] = $v['user_id'];
            $log_result_member = $memberM->getUser($where);
            $log_result[$k]['user_name'] = $log_result_member['nickname'];

        }
        $this->assign('log_result', $log_result);


        return $this->fetch();
    }

    //增加事件
    public function addThing()
    {

        $data = Request::instance();
        $list = $data->param();
        $thingM = new \app\index\model\Thing();
        if (isset($list['id'])) {

            $result = $thingM->updateTing($list);
        } else {

            $result = $thingM->addThing($list);

        }

        return $result;

    }

    //编辑
    public function editThing()
    {
        $thingM = new \app\index\model\Thing();
        $thingLogM = new ThingLog();

        $list = Request::instance();
        $parm = $list->param();

        $result = $thingM->editThing($parm);

        return $result;


    }

    /**项目中关联的日报页面
     * @return mixed
     */
    public function paper()
    {

        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $where['thing_id'] = input('thing_id');
        $dailyPlanM =   new  DailyPlan();
        $result =    $dailyPlanM->getWorkByDailyId($where);
        $this->assign('result',$result);
        return $this->fetch();
    }

    /**添加评论页面
     *
     */
    public function comment(){

        $userInfo = session('userInfo');
        $this->assign('userInfo', $userInfo);
        $thingLog['id'] =   input('thing_log_id');
        $thinglogM  =new ThingLog();
        $dailyCommentM  =new DailyComment();
        $thingLogResult =   $thinglogM->getByThingId($thingLog);
        if($thingLogResult==null||$thingLogResult==""){

            $this->redirect('Baocuo/index');
        }
        $dailyCommentWhere['thinglog_id']  =   input('thing_log_id');
        $dailyCommentResult =   $dailyCommentM->getAllByDailyPlanId($dailyCommentWhere);
        //统计出有几条评论，几个差评
        $commentData['number']  =   count($dailyCommentResult);//总共有几个评论
        $count=0;

        for($i=0;$i<$commentData['number'];$i++){

            if($dailyCommentResult[$i]['type']==1){

                $count+=1;
            }
        }
        $commentData['count']   =$count;
        $this->assign('commentData',$commentData);//评论的数目和差评的数目
        $this->assign('dailyCommentResult',$dailyCommentResult);
        $this->assign('thingLogResult',$thingLogResult['0']);

        return $this->fetch();

    }


}