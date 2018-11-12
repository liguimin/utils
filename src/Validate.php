<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/6
 * Time: 9:47
 */

namespace liguimin\utils;


class Validate
{
    /**
     * 验证邮箱
     * @param $email
     * @return bool
     */
    public static function isEmail($email){
        $preg_email='/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims';
        return preg_match($preg_email,$email)?true:false;
    }
}