<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/25
 * Time: 15:44
 */

namespace liguimin\utils;


use think\facade\Log;

class Fuc
{
    /**
     * 私有化构造函数，使外部只能使用类的静态方法
     * Fuc constructor.
     */
    private function __construct()
    {
    }


    /**
     * 十进制转换为26大写字母进制，如28=>AB
     * @param $num [十进制数]
     * @param bool $is_caps [true=转换为大写字母，false=转换为小写字母]
     * @return bool|string
     */
    public static function decimalToLetter($num, $is_caps = true)
    {
        if ($num <= 0) return false;
        $d = $is_caps ? 64 : 96;
        $str = '';
        while ($num > 0) {
            $rem = $num % 26;
            $str = chr($rem + $d) . $str;
            $num = floor($num / 26);
        }
        return $str;
    }

    /**
     * 无限极分类方法
     * @param $data 【要分类的数据集合】
     * @param int $pid 【父级编号】
     * @param string $pid_key_name 【父级编号的key名称】
     * @param string $id_key_name 【子编号的key名称】
     * @param string $child_key_name 【子集的key名称】
     * @param string $val_callback 【对树的每个子集做处理】
     * @return array
     */
    public static function getTree($data, $pid, $pid_key = 'pid', $id_key = 'id', $child_key = 'children',$val_callback=null)
    {
        $tree = [];
        foreach ($data as $key => $val) {
            //找到符合pid条件的数据
            if ($val[$pid_key] == $pid) {
                //销毁该条记录
                unset($data[$key]);
                //查找相应的子记录
                if (!empty($data)) {
                    $val[$child_key] = self::getTree($data, $val[$id_key], $pid_key, $id_key, $child_key,$val_callback);
                } else {
                    $val[$child_key] = [];
                }

                if($val_callback){
                    $val=call_user_func_array($val_callback,['val'=>$val]);
                }

                //添加记录
                $tree[] = $val;
            }
        }
        return $tree;
    }

    /**
     * 返回当前时间
     * @return bool|string
     */
    public static function getNow()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * 数组中每个元素的空格
     * @param $params
     * @return array
     */
    public static function trim($params)
    {
        $params = array_map(function ($v) {
            if (is_string($v) || is_numeric($v)) {
                $v = trim($v);
            }
            return $v;
        }, $params);
        return $params;
    }

    /**
     * 获取数组里的值，key不存在则返回默认值
     * @param $data
     * @param $key
     * @param null $default
     * @return null
     */
    public static function getValue($data, $key, $default = null)
    {
        return isset($data[$key]) ? $data[$key] : $default;
    }

    /**
     * 判断一个数组中的元素是否存在于另一个数组当中
     * @param array $needle
     * @param array $arr
     * @return bool
     */
    public static function isArrInArr(array $needle, array $arr)
    {
        foreach ($needle as $key => $val) {
            if (in_array($val, $arr)) return $val;
        }

        return false;
    }

    /**
     * 计算offset
     * @param $page
     * @param $limit
     * @return mixed
     */
    public static function getOffset($page, $limit)
    {
        return ($page - 1) * $limit;
    }

    /**
     * 判断是否为空
     * @param $var
     * @param bool $zero_is_empty
     * @return bool
     */
    public static function isEmpty($var, $zero_is_empty = true)
    {
        // 判断数据类型
        switch (gettype($var)) {
            case 'integer':
                return $zero_is_empty
                    ? (0 == $var ? true : false)             // ‘0’认为是空
                    : (0 != $var && !$var ? true : false);   // ‘0’不认为是空
                break;
            case 'string':
                return (0 == strlen($var)) ? true : false;
                break;
            default :
                return empty($var) ? true : false;
        }
    }

    /**
     * 将树形结构解析为数组，并在指定键值上($key)上加上某个字符串（$str）
     * @param $tree
     * @param $str
     * @param string $chname
     * @return array
     */
    public static function unTree($tree, $str, $key, $space = '&nbsp;', $chname = 'children')
    {
        $result = [];
        $space .= $space;
        foreach ($tree as $val) {
            $children = [];
            if (isset($val[$chname])) {
                $children = $val[$chname];
                unset($val[$chname]);
            }
            if ($val['pid'] != 0) {
                $val[$key] = $space . $str . $val[$key];
            }
            $result[] = $val;
            if (!empty($children)) {
                $children = self::unTree($children, $str, $key, $space);
                // $result[]=$children;
                $result = array_merge($result, $children);
            }
        }

        return $result;
    }

    /**
     * 获取当前日期
     * @return bool|string
     */
    public static function getNowDate()
    {
        return date('Y-m-d');
    }

    /**
     * 获取页数
     * @param $count
     * @param $limit
     * @return float
     */
    public static function getPageCount($count, $limit)
    {
        return ceil($count / $limit);
    }

    /**
     * 返回两个时间的月份差
     * @param $start_date
     * @param $end_date
     * @return bool|string
     */
    public static function getMonthDiff($start_date, $end_date)
    {
        $start_year = date('Y', strtotime($start_date));
        $end_year = date('Y', strtotime($end_date));
        $start_month = intval(date('m', strtotime($start_date)));
        $end_month = intval(date('m', strtotime($end_date)));

        $year_diff = $end_year - $start_year;
        $month_diff = $end_month - $start_month;

        $result = $year_diff * 12 + $month_diff;

        return $result;
    }

    /**
     * 获取表后缀（年_月）
     * @param $date
     * @return mixed
     */
    public static function getMonthTableSuffixByDate($date)
    {
        return date('Y_m', strtotime($date));
    }

    /**
     * 简化数字显示成xxx亿这样的格式
     * @param $num
     * @param int $base_num
     * @param string $subffix
     * @return float|string
     */
    public static function simNum($num, $base_num = 100000000, $subffix = '亿')
    {
        if (floor($num / $base_num) >= 1) {
            $num = round($num / $base_num, 6);
            $num = $num . $subffix;
        }
        return $num;
    }

    /**
     * 根据日期获取当天的最后时间
     * @param $day
     * @return string
     */
    public static function getDayEnd($day)
    {
        return $day . ' 23:59:59';
    }

    /**
     * 根据日期获取当天0点的时间
     * @param $day
     * @return string
     */
    public static function getDayStart($day)
    {
        return date('Y-m-d H:s:i', strtotime($day));
    }

    /**
     * 两个数相减，如果结果小于0则取0
     * @param $num1
     * @param $num2
     * @return int
     */
    public static function numDiffMinusToZero($num1, $num2)
    {
        $num_diff = $num1 - $num2;
        return $num_diff < 0 ? 0 : $num_diff;
    }

    /**
     * 数字简化
     * @param $num
     * @return float|string
     */
    public static function numSimp($num)
    {
        $num_abs=abs($num);
        if ($num_abs >= 100000000) {//转换亿
            $num_abs = $num_abs / 100000000;
            $num_abs .= '亿';
        } elseif ($num_abs >= 10000) {//转换万
            $num_abs = $num_abs / 10000;
            $num_abs .= '万';
        }

        if($num<0){
            $res='-'.$num_abs;
        }else{
            $res=$num_abs;
        }

        return $res;
    }

    /**
     * 检查设备类型（目前只有检查安卓和IOS）
     * @return string
     */
    public static function getDeviceType()
    {
        //全部变成小写字母
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $type = 'other';
        //分别进行判断
        if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
            $type = 'ios';
        } elseif (strpos($agent, 'android')) {
            $type = 'android';
        }
        return $type;
    }

    /**
     * 检查身份证是否正确
     * @param $id
     * @return bool
     */
     public static function is_idcard( $id )
    {
        $id = strtoupper($id);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if(!preg_match($regx, $id)){
            return FALSE;
        }
        //检查15位
        if(15==strlen($id)){
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth)){
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
            //检查18位
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            //检查生日日期是否正确
            if(!strtotime($dtm_birth)){
                return FALSE;
            }else{
                //检验18位身份证的校验码是否正确。
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ( $i = 0; $i < 17; $i++ )
                {
                    $b = (int) $id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($id,17, 1)){
                    return FALSE;
                }else{
                    return TRUE;
                }
            }
        }
    }

    /**
     * 根据二维数组中的某个key去掉其中的重复值（保留第一个重复值）
     * @param array $array
     * @param $key
     * @return array
     */
    public static function arrayUnique(array $array,$key){
        $result=[];
        foreach($array as $val){
            $is_exit=false;
            foreach($result as $v){
                if($val[$key]==$v[$key]){
                    $is_exit=true;
                }
            }
            if(!$is_exit){
                $result[]=$val;
            }
        }

        return $result;
    }

    /**
     * 生成16位订单号
     * @param $prefix
     * @return string
     */
    public static function createOrderNo($prefix){
       return $prefix.date('YmdHis').rand(10,99);
    }
}