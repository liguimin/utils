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
    public static function isEmail($email)
    {
        $preg_email = '/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims';
        return preg_match($preg_email, $email) ? true : false;
    }

    /**
     * 正则匹配手机号码
     * @param $mobile
     * @return int
     */
    public static function isMobile($mobile)
    {
        $preg_mobile = '/^[1][3,4,5,7,8][0-9]{9}$/';
        return preg_match($preg_mobile, $mobile);
    }

    /**
     * 验证是否是日期时间
     * @param $date
     * @return bool
     */
    public static function is_datetime($datetime)
    {
        if ($datetime == date('Y-m-d H:i:s', strtotime($datetime))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证是否是日期
     * @param $date
     * @return bool
     */
    public static function is_date($date)
    {
        //匹配日期格式
        if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
            //检测是否为日期
            if (checkdate($parts[2], $parts[3], $parts[1]))
                return true;
            else
                return false;
        } else {
            return false;
        }
    }

}