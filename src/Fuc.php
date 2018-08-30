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
    public static function getTree(array $data,$pid,$pid_key_name='pid',$id_key_name='id',$child_key_name='child'){
        $tree=[];
        foreach($data as $key=>$val){
            if($val[$pid_key_name]==$pid){
                $tree[]=$val;
                unset($data[$key]);
            }
        }

        foreach($tree as $key=>$val){
            $tree[$key][$child_key_name]=self::getTree($data,$val[$id_key_name],$pid_key_name,$id_key_name,$child_key_name);
        }

        return $tree;
    }
}