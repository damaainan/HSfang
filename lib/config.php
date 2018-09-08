<?php 
// 配置文件
namespace Tools;

class Config{
    public static  function getConfig($str){
        $config = [
            "fang" => array(
                "title" => array(".article_row_fluid h1", 'text'),
                "source" => array(".article_meta .source a", 'text'),
                "time" => array(".article_meta .timestamp", 'text'),
                "body" => array("#nei", 'html'),
            ),
            "anju" => array(
                "title" => array("h1", 'text'),
                "source" => array("h1 a", 'href'),
                "time" => array(".article_r .link_postdate", 'text'),
                "body" => array("#article_content", 'html'),
            ),
            "souhu" => array(
                "title" => array("#articleTitle a", 'text'),
                "source" => array("#articleTitle a", 'href'),
                "body" => array(".article__content", 'html'),
            ),
        ];
        return $config[$str];
    }

    /**
     * 获取页面链接集合
     * @param  string $name 
     * @return array
     */
    public static function getListConfig($name){
        $config = [
            "tuicool" => array(
                "url" => array(".single_simple span a", 'href')
            ),
            "csdn" => array(
                "url" => array(".article_row_fluid h1", 'text')
            ),
            "segmentfault" => array(
                "url" => array(".summary h2 a", 'href')
            ),
            
        ];
        return $config[$name];
    }
}
