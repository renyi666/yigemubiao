<?php
namespace app\index\ceshi;
use think\Exception;

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
 * @created_time：2017/2/18 16:33
 * When I wrote this, only God and I understood what I was doing
 * Now, God only knows
 */
class testException extends  Exception
{


    public  function  errorMessage(){

        $errormsg=   "error on line ".$this->getLine()."in" .$this->getFile();
        return $errormsg;

    }


}