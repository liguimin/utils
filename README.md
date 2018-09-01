## 安装
发布版本  
```
composer require liguimin/utils  
```
开发版本  
```
composer require liguimin/utils:dev-master  
```

## 简介
Func.php 一些常用函数  
Curl.php 简洁方便的进行get、post、上传文件等操作
## 使用方法
### Curl类
#### 快速入门
发起get请求
``` php
require 'vendor/autoload.php';

$curl = new \liguimin\utils\Curl();
//发起get请求
$curl->get('http://www.test.com?test=test');
//检查是否请求成功，并处理返回内容
if($curl->is_success()){//请求成功
    $body = $curl->getResponseBody();
    echo $body;

    //接下来可以为所欲为了

}else{//请求失败，打印错误信息
    $err_msg=$curl->getErrMsg();
    echo $err_msg;
}
```
发起post请求
``` php
require 'vendor/autoload.php';

$curl = new \liguimin\utils\Curl();
//post参数
$params = [
    'user'     => 'test',
    'password' => 'test',
];
//发起get请求
$curl->post('http://www.test.com',$params);
//检查是否请求成功，并处理返回内容
if($curl->is_success()){//请求成功
    $body = $curl->getResponseBody();
    echo $body;

    //接下来可以为所欲为了

}else{//请求失败，打印错误信息
    $err_msg=$curl->getErrMsg();
    echo $err_msg;
}
```
上传文件(post)
``` php
require 'vendor/autoload.php';

$curl = new \liguimin\utils\Curl();
//要上传的文件
$filename='test.txt';
//执行上传
$curl->uploadFile('http://www.test.com/',$filename);
//检查是否请求成功，并处理返回内容
if($curl->is_success()){//请求成功
    $body = $curl->getResponseBody();
    echo $body;

    //接下来可以为所欲为了

}else{//请求失败，打印错误信息
    $err_msg=$curl->getErrMsg();
    echo $err_msg;
}
```
#### 常用选项及函数
常用选项设置
``` php
//设置请求的url
$curl->setUrl('http://www.test.com');
//设置超时时间（默认30s）
$curl->setTimeout(20);
//设置浏览器标识
$curl->setUseragent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
//设置请求源
$curl->setReferer('http://www.testreferer.com');
//进行http basic认证
$curl->useAuth('username', 'password');
//开启时会将响应头信息作为数据流输出 true开启(默认)  false关闭
$curl->setIncludeHeader(true);
//开启时会将html中的body部分忽略 true开启  false关闭（默认）
$curl->setNoBody(true);
//是否跟踪页面的重定向 true是（默认） false否
$curl->setFollowLocaltion(true);
//开启时curl_exec会返回字符串而不是直接输出 true开启（默认） false直接输出
$curl->setReturntransfer(true);
//设置请求方法
$curl->setMethod('get');
//设置请求参数
$curl->setData([
    'username' => 'test',
    'password' => 'test',
]);
//是否进行证书验证(https) true是  false否(默认)
$curl->setSslVerify(false);
//设置请求头
$curl->addRequestHeader('Content-type', 'text/plain');//添加单个
$curl->addRequestHeader([
    'Content-type'   => 'text/plain',
    'Content-length' => '100',
]);//使用数组批量添加

```
