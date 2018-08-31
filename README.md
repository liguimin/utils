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
$curl->useAuth('username','password');
//开启时会将响应头信息作为数据流输出 true开启(默认)  false关闭
$curl->setIncludeHeader(true);
```
