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
##### 发起get请求
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
##### 发起post请求
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
##### 上传文件(post)
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
#### 选项设置
##### 设置请求的url
设置请求的url，如果最后在发起请求的时候传入了url参数，则会覆盖本函数设置的url
``` php
//设置url
$curl->setUrl('http://www.test.com?test=test');
//发起请求（注意此处可不传url参数）
$curl->get();
```
