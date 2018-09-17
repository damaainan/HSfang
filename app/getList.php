<?php 

header("Content-type:text/html; Charset=utf-8");
// set_time_limit(0);
require "../vendor/autoload.php";

// 获取  详情页 地址 和 概况
use Tools\GetContent;
// require "../lib/getContent.class.php";

// 从脚本参数获取地址  或者 从 文件获取一系列地址
function deal($argc, $argv){
	// 从文件中获取列表页 
    $urls = [];
    if ($argv[1] === "urls.txt") {
        // 从文本获取地址
        $fp = fopen("urls.txt", "r");
        while (!feof($fp)) {
            $href = fgets($fp);
            $href = trim($href);
            // var_dump($href);
            if ($href) {
                $urls[] = $href;
            }
        }
        fclose($fp);
    } else {
        for ($i = 1; $i < $argc; $i++) {
            $urls[] = $argv[$i];
        }
    }

    // 解析列表页获取详情页 
    $Mark = new GetContent();

    for ($i = 0, $len = count($urls); $i < $len; $i++) {
        $url = $urls[$i];
        // 获取概况
        $ret = $Mark->getListUrl($url);
        if($i%10==0){
            sleep(1);
        }
        // echo $ret;
	    foreach ($ret as $val) {
	    	echo $val,"\r\n";
	    	// 写入数据库
	    }
    }
}

deal($argc, $argv);

