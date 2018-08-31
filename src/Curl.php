<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/28
 * Time: 10:51
 */

namespace liguimin\utils;


class Curl
{
    private $url;//资源地址

    private $options = [];//请求选项

    private $timeout = 30;//超时时长（s）

    private $useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1';//浏览器类型

    private $referer = '';//请求来源

    private $authentication = 0;//是否需要传递http basic 认证 0不需要 1需要

    private $auth_name = '';//http basic认证用户名

    private $auth_pwd = '';//http basic认证密码

    private $includeHeader = 1;//是否将http头部信息作为数据流输出 0否  1是

    private $no_body = 0;//启用时不输出html的body部分 0禁用 1启用

    private $header = [];//http头字段

    private $followlocation = true;//是否跟踪重定向的页面

    private $returntransfer = true;//TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。

    private $http_code;//响应的http状态码

    private $data;//请求参数

    private $method = 'GET';//请求方法

    private $response;//响应的内容

    private $curl_info;//获取最后一次传输的相关信息

    private $response_header;//响应的头部

    private $response_body;//响应的主体部分

    private $err_msg;//错误信息

    private $err_code;//错误码

    private $curl_err_code=0;//最后一次curl错误码

    private $curl_err_msg;//最后一次curl错误信息

    private $response_header_size;//最后一次传输的http头部的大小


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
    protected function addOption($name, $val = null)
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
     * @param null $name
     * @return array|null
     */
    protected function getOption($name = null)
    {
        if ($name != null) {
            return isset($this->options[$name]) ? $this->options[$name] : null;
        }
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
     * 获取是否输出http头部信息
     * @return int
     */
    public function getIncludeHeader()
    {
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
     * 获取是否不输出html的body部分的值
     * @return int
     */
    public function getNoBody()
    {
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
     * @param null $data
     * @param null $method
     * @param array $options
     * @param string $postname
     * @param string $mimetype
     * @return $this|Curl
     */
    public function request($url = null, $data = null, $method = null, $options = [], $postname = '', $mimetype = '')
    {
        if ($url) {
            $this->setUrl($url);
        }

        if ($method) {
            $this->setMethod($method);
        }

        if (!empty($data)) {
            $this->setData($data);
        }

        $r_options = [
            CURLOPT_URL            => $this->getUrl(),//请求url
            CURLOPT_TIMEOUT        => $this->getTimeout(),//超时时间
            CURLOPT_FOLLOWLOCATION => $this->getFollowLocaltion(),//是否跟踪页面重定向
            CURLOPT_RETURNTRANSFER => $this->getReturntransfer(),//TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
            CURLOPT_USERAGENT      => $this->getUseragent(),//浏览器类型
            CURLOPT_HEADER         => $this->getIncludeHeader(),//是否输出头部
        ];
        //设置http头部
        if (!empty($this->getHeader())) {
            $r_options[CURLOPT_HTTPHEADER] = $this->getHeader();
        }
        //设置请求源
        if (!empty($this->getReferer())) {
            $r_options[CURLOPT_REFERER] = $this->getReferer();
        }
        //是否忽略html中的body的内容
        if ($this->getNoBody()) {
            $r_options[CURLOPT_NOBODY] = $this->getNoBody();
        }
        //http basic验证
        if ($this->authentication == 1) {
            $r_options[CURLOPT_USERPWD] = $this->auth_name . ':' . $this->auth_pwd;
        }
        //是否输出http头部
        if ($this->getIncludeHeader() == 1) {
            $r_options[CURLOPT_HEADER] = $this->getIncludeHeader();
        }


        //初始化句柄
        $ch = curl_init();
        try {
            //批量设置curl选项
            $this->addOption($r_options);
            //按请求方法设置选项
            switch ($this->getMethod()) {
                case 'GET':
                    $this->handleGet($url, $this->getData());
                    break;
                case 'POST':
                    $this->handlePost($url, $this->getData());
                    break;
                case 'FILE':
                    $this->handleFile($url, $this->getData(), $postname, $mimetype);
                    break;
            }
            //如果有传入选项，则进行设置
            if (!empty($options)) {
                $this->addOption($options);
            }


            //批量设置选项
            curl_setopt_array($ch, $this->getOption());
            //发起请求
            $this->response = curl_exec($ch);
            //最后一次curl操作的错误码
            $this->curl_err_code=curl_errno($ch);
            if($this->curl_err_code!=0){
                //最后一次curl操作的错误信息
                $this->curl_err_msg=curl_error($ch);
                throw new \Exception($this->curl_err_msg);
            }
            //最后一次传输的相关信息
            $this->curl_info = curl_getinfo($ch);
        } catch (\Exception $e) {
            return $this->handleException($e);
        } catch (\Error $e) {
            return $this->handleException($e);
        } finally {
            //关闭curl
            curl_close($ch);
        }

        return $this;
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


    /**
     * 处理文件上传
     * @param $url
     * @param $data
     * @param string $postname
     * @param string $minetype
     * @throws \Exception
     */
    private function handleFile($url, $data, $postname = '', $minetype = '')
    {
        $postname = $postname ? $postname : 'file';
        if (!file_exists($data)) throw new \Exception('文件不存在:' . $data);
        $data = [$postname => $this->curl_file_create($data, $minetype, '')];
        $this->handlePost($url, $data);
    }


    /**
     * post请求
     * @param $url
     * @param null $data
     * @param array $options
     * @return $this|Curl
     */
    public function post($url, $data = null, $options = [])
    {
        return $this->request($url, $data, 'POST', $options);
    }


    /**
     * get请求
     * @param $url
     * @param null $data
     * @param array $options
     * @return $this|Curl
     */
    public function get($url, $data = null, $options = [])
    {
        return $this->request($url, $data, 'GET', $options);
    }


    /**
     * 上传文件
     * @param $url
     * @param $file
     * @param array $options
     * @param string $postname
     * @param string $mimetype
     * @return $this|Curl
     */
    public function uploadFile($url, $file, $options = [], $postname = '', $mimetype = '')
    {
        return $this->request($url, $file, 'FILE', $options, $postname, $mimetype);
    }


    /**
     * 获取最后一次传输的信息
     * @param null $name
     * @return null
     */
    public function getCurlInfo($name = null)
    {
        if (!empty($name)) {
            return isset($this->curl_info[$name]) ? $this->curl_info[$name] : null;
        }
        return $this->curl_info;
    }


    /**
     * 获取响应的所有内容
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * 获取响应的http状态码
     * @return mixed
     */
    public function getHttpCode()
    {
        if (!$this->http_code) {
            $this->http_code = $this->getCurlInfo('http_code');
        }
        return $this->http_code;
    }

    /**
     * 获取最后一次传输的http头部大小
     * @return null
     */
    public function getResponseHeaderSize()
    {
        if (!$this->response_header_size) {
            $this->response_header_size = $this->getCurlInfo('header_size');
        }

        return $this->response_header_size;
    }


    /**
     * 获取http响应头部
     * @param null $name
     * @return null
     */
    public function getResponseHeader($name=null)
    {
        if ($this->getIncludeHeader()&&!$this->response_header && $this->getResponseHeaderSize()) {
            //从返回的内容中切割出响应头的字符串
            $header = substr($this->getResponse(), 0, $this->getResponseHeaderSize());
            //按换行符将字符串切割成数组
            $header=explode("\r\n",$header);
            //去掉数组中的空元素
            //$this->response_header=array_filter($header);
            foreach($header as $key=>$val){
                if($val){
                    $arr=explode(': ',$val,2);
                    if($key==0){
                        $this->response_header['status']=$arr[0];
                    }else{
                        $this->response_header[$arr[0]]=$arr[1];
                    }
                }
            }
        }
        if(!empty($name)){
            return isset($this->response_header[$name])?$this->response_header[$name]:null;
        }
        return $this->response_header;
    }


    /**
     * 获取请求
     * @return string
     */
    public function getResponseBody(){
        if(!$this->response_body){
            if($this->getIncludeHeader()&&$this->getResponseHeaderSize()){
                $this->response_body=substr($this->getResponse(),$this->getResponseHeaderSize());
            }else{
                $this->response_body=$this->getResponse();
            }
        }

        return $this->response_body;
    }

    /**
     * 获取最后一次curl操作的错误码
     * @return int
     */
    public function getCurlErrCode(){
        return $this->curl_err_code;
    }

    /**
     * 获取最后一次curl操作的错误信息
     * @return mixed
     */
    public function getCurlErrMsg(){
        return $this->curl_err_msg;
    }

    /**
     * 请求是否成功
     * @return bool
     */
    public function is_success(){
        $http_code=$this->getHttpCode();
        if($http_code<200||$http_code>299){
            return false;
        }
        return true;
    }


    /**
     * 异常处理方法
     * @param \Exception $e
     * @return $this
     */
    public function handleException(\Exception $e)
    {
        $this->err_msg = $e->getMessage();
        return $this;
    }

    /**
     * 获取错误信息
     * @return mixed
     */
    public function getErrMsg(){
        return $this->err_msg;
    }

    /**
     * 创建post文件
     * @param $filename
     * @param string $mimetype
     * @param string $postname
     * @return \CURLFile|string
     */
    private function curl_file_create($filename, $mimetype = '', $postname = '')
    {
        if (!function_exists('curl_file_create')) {//php<5.5
            return "@$filename;filename="
            . ($postname ?: basename($filename))
            . ($mimetype ? ";type=$mimetype" : '');
        } else {//php>=5.5
            return curl_file_create($filename, $mimetype, $postname);
        }
    }
}