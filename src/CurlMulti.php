<?php
/**
 * 批量发送请求
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/1
 * Time: 15:58
 */

namespace liguimin\utils;


class CurlMulti
{
    private $ch_list = [];//已添加的句柄

    /**
     * 添加curl句柄
     * @param $ch
     * @return array
     */
    public function addHandle($ch)
    {
        if(is_array($ch)){
            $this->ch_list=$ch+$this->ch_list;
        }else{
            $this->ch_list[] = $ch;
        }
        return $this->ch_list;
    }

    /**
     * 获取已添加的curl句柄
     * @return array
     */
    public function getHandle()
    {
        return $this->ch_list;
    }


    /**
     * 执行请求
     * @param array $ch_list
     * @return array|\Generator
     */
    public function exec($ch_list=[])
    {
        $this->addHandle($ch_list);
        $ch_list=$this->getHandle();

        $mh = curl_multi_init();
        //批量添加curl句柄
        foreach($ch_list as $key=>$ch){
            curl_multi_add_handle($mh, $ch);
        }

        $acitve = 0;
        // 执行批处理句柄
        do {
            curl_multi_exec($mh, $acitve);
            curl_multi_select($mh);
        } while ($acitve > 0);

        //处理返回值，移除句柄
        foreach($ch_list as $key=>$ch){
            $content['err_msg']=curl_error($ch);
            $content['err_code']=curl_errno($ch);
            if(!empty($content['err_msg'])){
                $content['data']='';
            }else{
                $content['data']=curl_multi_getcontent($ch);
            }
            yield $content;
            //关闭当前句柄
            curl_multi_remove_handle($mh, $ch);
        }

        curl_multi_close($mh);
    }
}