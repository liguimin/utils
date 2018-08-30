<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/28
 * Time: 10:51
 */

namespace liguimin\utils\curl;


class Curl
{
    private $url;//资源地址

    private $options = [];//请求选项

    private $timeout = 30;//超时时长（s）

    private $useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';//浏览器类型

    private $referer = '';//请求来源

    private $authentication = 0;//是否需要传递http basic 认证 0不需要 1需要

    private $auth_name = '';//http basic认证用户名

    private $auth_pwd = '';//http basic认证密码

    private $includeHeader = 0;//是否将http头部信息作为数据流输出 0否  1是

    private $no_body = 0;//启用时不输出html的body部分 0禁用 1启用

    private $header = [];//http头字段

    private $followlocation = true;//是否跟踪重定向的页面

    private $returntransfer = true;//TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。

    private $curl_info = [];//curl连接句柄的信息

    private $http_code;//响应的http状态码

    private $data;//请求参数

    private $method = 'GET';//请求方法

    private $e;//错误信息


    /**
     * 设置请求的url
     * @param $url
     * @return mixed
     */
    public function setUrl($url)
    {
        return $this->url = $url;
    }

    /**
     * 获取请求的url
     * @return bool
     */
    public function getUrl()
    {
        return is_null($this->url) ? false : $this->url;
    }

    /**
     * 设置curl选项
     * @param $name
     * @param null $val
     * @return array
     */
    public function addOption($name, $val = null)
    {
        if (is_array($name)) {//数组批量传入
            $this->options = $name + $this->options;
        } else {//设置单个
            $this->options[$name] = $val;
        }
        return $this->options;
    }

    /**
     * 获取curl选项
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * 设置超时时长
     * @param $time
     * @return mixed
     */
    public function setTimeout($time)
    {
        return $this->timeout = $time;
    }

    /**
     * 获取超时时长
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * 设置浏览器类型
     * @param $useragent
     * @return mixed
     */
    public function setUseragent($useragent)
    {
        return $this->useragent = $useragent;
    }

    /**
     * 获取浏览器类型
     * @return string
     */
    public function getUseragent()
    {
        return $this->useragent;
    }

    /**
     * 设置请求来源
     * @param $referer
     * @return mixed
     */
    public function setReferer($referer)
    {
        return $this->referer = $referer;
    }

    /**
     * 获取请求源
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * 开启http basic认证
     * @param $auth_name
     * @param $auth_pwd
     */
    public function useAuth($auth_name, $auth_pwd)
    {
        $this->authentication = 1;
        $this->auth_name = $auth_name;
        $this->auth_pwd = $auth_pwd;
    }

    /**
     * 设置是否输出http头部信息
     * @param $bool
     * @return int
     */
    public function setIncludeHeader($bool)
    {
        if ($bool) {
            $this->includeHeader = 1;
        }
        return $this->includeHeader;
    }

    /**
     * 设置是否不输出html的body部分
     * @param $bool
     * @return int
     */
    public function setNoBody($bool)
    {
        if ($bool) {
            $this->no_body = 1;
        }
        return $this->no_body;
    }


    /**
     * 设置请求头
     * @param $name
     * @param null $val
     * @return array
     */
    public function addHeader($name, $val = null)
    {
        if (is_array($name)) {
            $this->header = array_merge($this->header, $name);
        } else {
            $this->header[$name] = $val;
        }

        return $this->header;
    }

    /**
     * 获取http请求头部信息
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * 设置是否跟踪页面的重定向
     * @param $bool
     * @return bool
     */
    public function setFollowLocaltion($bool)
    {
        if (!$bool) {
            $this->followlocation = false;
        } else {
            $this->followlocation = true;
        }
        return $this->followlocation;
    }

    /**
     * 获取是否跟踪页面的重定向
     * @return bool
     */
    public function getFollowLocaltion()
    {
        return $this->followlocation;
    }

    /**
     * 设置是否返回字符串，而不是直接输出
     * @param $bool
     * @return bool
     */
    public function setReturntransfer($bool)
    {
        return $this->returntransfer = $bool ? true : false;
    }

    /**
     * 获取returntransfer值
     * @return bool
     */
    public function getReturntransfer()
    {
        return $this->returntransfer;
    }


    /**
     * 执行curl获取返回值
     * @param null $url
     * @param null $method
     * @param null $data
     * @return mixed
     */
    public function request($url = null, $data = null, $method = null)
    {
        if ($url != null) {
            $this->setUrl($url);
        }

        if ($method != null) {
            $this->setMethod($method);
        }

        if (!empty($data)) {
            $this->setData($data);
        }

        $options = [
            CURLOPT_URL            => $this->url,//请求url
            CURLOPT_TIMEOUT        => $this->timeout,//超时时间
            CURLOPT_FOLLOWLOCATION => $this->followlocation,//是否跟踪页面重定向
            CURLOPT_RETURNTRANSFER => $this->returntransfer,//TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
            CURLOPT_USERAGENT      => $this->useragent,//浏览器类型
            CURLOPT_HEADER         => $this->includeHeader,//是否输出头部
        ];
        //设置http头部
        if (!empty($this->header)) {
            $options[CURLOPT_HTTPHEADER] = $this->header;
        }
        //设置请求源
        if (!empty($this->referer)) {
            $options[CURLOPT_REFERER] = $this->referer;
        }
        //是否忽略html中的body的内容
        if ($this->no_body) {
            $options[CURLOPT_NOBODY] = $this->no_body;
        }
        //http basic验证
        if ($this->authentication == 1) {
            $options[CURLOPT_USERPWD] = $this->auth_name . ':' . $this->auth_pwd;
        }
        //是否输出http头部
        if ($this->includeHeader == 1) {
            $options[CURLOPT_USERPWD] = $this->auth_name . ':' . $this->auth_pwd;
        }

        //初始化句柄
        $ch = curl_init();
        try{
            //批量设置curl选项
            $this->addOption($options);
            //按请求方法设置选项
            switch ($this->method) {
                case 'GET':
                    $this->handleGet($url, $this->getData());
                    break;
                case 'POST':
                    $this->handlePost($url, $this->getData());
                    break;
                case 'FILE':
                    $this->handleFile($url,$this->getData());
                    break;
            }
            //批量设置选项
            curl_setopt_array($ch, $this->options);
            //发起请求
            $response = curl_exec($ch);
            //获取响应的http状态码
            $this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        } catch(\Exception $e){
            $this->handleException($e);
            $response=false;
        }catch(\Error $e){
            $this->handleException($e);
            $response=false;
        } finally{
            //关闭curl
            curl_close($ch);
        }

        return $response;
    }

    /**
     * 设置请求方法
     * @param $method
     * @return string
     */
    public function setMethod($method)
    {
        return $this->method = strtoupper($method);
    }

    /**
     * 获取请求方法
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * 设置请求参数
     * @param $data
     * @return mixed
     */
    public function setData($data)
    {
        return $this->data = $data;
    }


    /**
     * 获取请求参数
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 处理GET请求
     * @param $url
     * @param $data
     */
    private function handleGet($url, $data)
    {
        if (is_array($data) && !empty($data)) {
            $data = http_build_query($data);
        }
        if (!empty($data)) {
            $data = ltrim($data, '?');
            $url .= "?{$data}";
        }
        $this->addOption(CURLOPT_URL, $url);
    }

    /**
     * 处理POST请求
     * @param $url
     * @param $data
     */
    private function handlePost($url, $data)
    {
        $this->addOption(CURLOPT_POST, true);
        if (!empty($data)) {
            $this->addOption(CURLOPT_POSTFIELDS, $data);
        }
    }

    private function handleFile($url,$data){
        if(!file_exists($data)) throw new \Exception('文件不存在');
    }

    /**
     * post请求
     * @param $url
     * @param $data
     * @return mixed
     */
    public function post($url, $data = null)
    {
        return $this->request($url, $data, 'POST');
    }

    /**
     * get请求
     * @param $url
     * @param $data
     * @return mixed
     */
    public function get($url, $data = null)
    {
        return $this->request($url, $data, 'GET');
    }

    /**
     * 上传文件
     * @param $url
     * @param $file
     * @return mixed
     */
    public function uploadFile($url,$file){
        return $this->request($url,$file,'FILE');
    }

    /**
     * 获取响应的http状态码
     * @return mixed
     */
    public function getHttpCode()
    {
        return $this->http_code;
    }

    public function handleException(\Exception $e){
        $this->e=$e;
    }
}