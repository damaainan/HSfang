<?php
namespace Tools\lib;
use phpQuery;
use QL\QueryList;
use Tools\ToolUtil;

// header("Content-type:text/html; Charset=utf-8");
class Loupan {
    public static function getLoupan($html, $rules, $url) {
        $data = QueryList::html($html)->rules($rules)->query()->getData();
        $ret = $data->all();

        // 清洗数据
        $title = $ret[0]['title'];
        $source = isset($ret[0]['source']) ? $ret[0]['source'] : $url;
        $time = isset($ret[0]['time']) ? $ret[0]['time'] : '';
        $body = $ret[0]['body'];

        // 清洗数据 

        return $content;
    }

}