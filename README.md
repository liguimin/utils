## 安装
发布版本  
```
composer require liguimin/utils  
```
开发版本  
```
composer require liguimin/utils:dev-master  
```

## 类功能简介
Func.php 存放一些常用函数  
Curl.php 封装curl，在使用时能更简便的进行post、get、上传文件等操作  
## 使用方法
### Curl类
#### 快速入门
发起get请求
``` php
require 'vendor/autoload.php';

$curl = new \liguimin\utils\Curl();
//发起get请求
$curl->get('http://www.baidu.com');
//获取主体部分（不含http响应头）
$body=$curl->getResponseBody();
echo $body;
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
$curl->post('http://www.baidu.com',$params);
//获取主体部分（不含http响应头）
$body = $curl->getResponseBody();
echo $body;
```
上传文件(post)
``` php
require 'vendor/autoload.php';

$curl = new \liguimin\utils\Curl();
//要上传的文件
$filename='test.txt';
//执行上传
$curl->uploadFile('http://www.test.com/',$filename);
//获取主体部分（不含http响应头）
$body = $curl->getResponseBody();
echo $body;
```
