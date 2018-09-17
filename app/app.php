<?php
header("Content-type:text/html; Charset=utf-8");
// set_time_limit(0);
require "../vendor/autoload.php";

use Tools\GetContent;

function deal(){
	// 获取详情页地址 
		// 从数据库读取
	$urls = [''];
	
	// 解析
	$Mark = new GetContent();

    for ($i = 0, $len = count($urls); $i < $len; $i++) {
        $url = $urls[$i];
        $ret = $Mark->doMark($url);
        if($i%10==0){
            sleep(1);
        }
        echo $ret;
    }
	// 存储 放在 解析出结果之后
}