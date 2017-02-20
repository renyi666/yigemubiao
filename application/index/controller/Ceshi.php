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
 * @created_time：2017/2/10 11:05
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */

namespace app\index\controller;


use app\index\ceshi\testException;
use app\index\model\Daily;
use app\index\model\Member;
use think\Cache;
use think\Controller;
use think\Request;
use think\Validate;

class Ceshi
{

    public  function  index(){

            return "ccc";
    }
    public  function myname(){
        if(Request::instance()->isPost()){
            dump("post");
        }
        $list['name']   =   input('name');
        return $list['name'];

    }
    public  function ceshiRedis(){


        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);
        echo "success";
        echo "running ".$redis->ping();

        if(!Cache::get('password')){
            Cache::store('redis')   ->set('password','password');
        }

        dump(Cache::get('password'));


        Cache::store('redis')->set('name','value');
        $a  =Cache::get('name');
        dump($a);
    }

    public function ceshiyanzheng(){

        $validate = new Validate([
            'name'  => 'require|max:2',
            'email' => 'email'
        ]);
        $data = [
            'name'  => 'thinkphp',
            'email' => 'thinkphp@qq.com'
        ];
        if (!$validate->check($data)) {

            dump($validate->getError());
        }
        dump("ccc");


    }

    public  function _empty(){

        dump("cccc");
        exit();
    }
    // 关联新增数据
    public function add()
    {
        $user               = Member::get(1);
        $book               = new Daily();
        $book->content        = 'ThinkPHP5快速入门';
        $book->limit_time = rand(77777,54545454554545);
        $user->profile()->save($book);
        return '添加Book成功';
    }
    public  function getdata(){

        $user   =   Member::all();

        foreach ($user  as $key =>$value){

            $user['profile']    =  $value->profile ;

        }
        dump($user);
        exit();
        dump($user->profile()->select());
    }
    public  function xeshiexce($num){

        if($num>1){

            throw  new testException("value must <1");
        }

    }
    public  function ceshiexce(){

        try{
            $this->xeshiexce(2);
        }catch (testException $e){

            echo  "Message".$e->getMessage();
        }
    }


}