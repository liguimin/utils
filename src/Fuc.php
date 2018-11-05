<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/25
 * Time: 15:44
 */

namespace liguimin\utils;


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
    public static function decimalToLetter($num,$is_caps=true){
        if($num<=0) return false;
        $d=$is_caps?64:96;
        $str='';
        while($num>0){
            $rem=$num%26;
            $str=chr($rem+$d).$str;
            $num=floor($num/26);
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
     * @return array
     */
    public static function getTree(array $data,$pid,$pid_key='pid',$id_key='id',$child_key='children'){

        $tree = [];
        foreach ($data as $key => $val) {
            //找到符合pid条件的数据
            if ($val[$pid_key] == $pid) {
                //销毁该条记录
                unset($data[$key]);
                //查找相应的子记录
                if (!empty($data)) {
                    $val[$child_key] = self::getTree($data, $val[$id_key]);
                } else {
                    $val[$child_key] = [];
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
    public static function getNow(){
        return date('Y-m-d H:i:s');
    }

    /**
     * 数组中每个元素的空格
     * @param $params
     * @return array
     */
    public static function trim($params){
        $params = array_map(function ($v) {
            if(is_string($v)||is_numeric($v)){
                $v=trim($v);
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
    public static function getValue($data,$key,$default=null){
        return isset($data[$key])?$data[$key]:$default;
    }
}