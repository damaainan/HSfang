<?php

// 正则 字符串处理
header('Content-Type:text/html; charset=utf-8');

// include("phpQuery/phpQuery.php");

include "db.class.php";

// $db=new Fang();

require 'vendor/autoload.php';
use QL\QueryList;

$db = Fang::getInstance();

set_time_limit(0);

function getAllPage()
{
//获取完整列表页
    $urls = [];
    for ($i = 1; $i < 6; $i++) {
        // $urls[$i] = "http://newhouse.hs.fang.com/house/s/taochengqu/b9" . $i . "/";
        $urls[$i] = "http://newhouse.hs.fang.com/house/s/a77-b91/?ctm=1.hs.xf_search.page." . $i . "/";
    }
    return $urls;

}
function openUrl($url)
{
    $ch      = curl_init();
    $timeout = 3000; // set to zero for no timeout
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $handles = curl_exec($ch);
    curl_close($ch);
    return $handles;
} //CURLOPT_REFERER

$arr = getAllPage();
// var_dump($arr);

// foreach ($arr as $val) {
// $url=$arr[1];

// var_dump($data);

function getData($url)
{
    $html = openUrl($url);

    $arrstr = explode('<body', $html);
    // var_dump($arrstr);
    $result = explode('</body>', $arrstr[1]);
    $str    = $result[0];
    $str    = preg_replace('/<script(.*?)<\/script>/', '', $str);
    $str    = preg_replace('/<script (.*?)<\/script>/', '', $str);
    $str    = iconv("GB2312", "UTF-8//IGNORE", $str);
    // echo $str;
    // echo $url;
    $rules = array(
        //采集class为two下面的超链接的链接
        'link'  => array('h4 a', 'href'),
        'name'  => array('h4 a', 'text'),
        'price' => array('h5 span', 'text'),
        'sale'  => array('.pt08 .fl span:first a', 'text'),
        'rent'  => array('.pt08 .fl span:last a', 'text'),
    );
    $data = QueryList::Query($str, $rules, '.contentList .sslalone', null, null)->data;

    // var_dump($data);
    return $data;
}

$result = [];
foreach ($arr as $value) {
    $url    = $value;
    $data   = getData($url);
    $result = array_merge($result, $data);
}

// var_dump($result);

$table = "f_list";

foreach ($result as $val) {
    $dataarr = [
        'url'   => $val['link'],
        'name'  => $val['name'],
        'price' => intval($val['price']),
        'sale'  => intval($val['sale']),
        'rent'  => intval($val['rent']),
    ];
    $str1 = "select * from f_list where name='" . $val['name'] . "';";
    // var_dump($str1);
    $rst = $db->query($str1, "Row");

    if (!$rst) {
        $count = $db->insert($table, $dataarr);
    }
}

//数据拿到了 存入数据库
