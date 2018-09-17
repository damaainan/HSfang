<?php 
// 配置文件
namespace Tools;

class Config{
    public static  function getConfig($str){
        $config = [

            "leju" => array(
                "title" => array("#articleTitle a", 'text'),
                "source" => array("#articleTitle a", 'href'),
                "body" => array(".article__content", 'html'),
            ),
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
            "leju" => array(
                "url" => array("#ZT_searchBox .b_card .b_titBox h2 a", 'href'),
                "name" => array("", 'text'),
            ),
            "csdn" => array(
                "url" => array(".article_row_fluid h1", 'text')
            ),
            
        ];
        return $config[$name];
    }
}
