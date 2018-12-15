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

    /**
     * 正则匹配手机号码
     * @param $mobile
     * @return int
     */
    public static function isMobile($mobile){
        $preg_mobile='/^[1][3,4,5,7,8][0-9]{9}$/';
        return preg_match($preg_mobile,$mobile);
    }
}