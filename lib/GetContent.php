<?php
namespace Tools;

// header("Content-type:text/html; Charset=utf-8");
require "../vendor/autoload.php";
// require '../phpQuery/phpQuery.php';
// require "replaceElement.calss.php";

// require "config.php"; // 能不能不用引入的方式？

use QL\QueryList;
//use Tools\replaceElement;
//use Tools\GetHtml;
//use Tools\Config; // 同一命名空间下 会自动寻找
use Tools\lib\Fang;
use Tools\lib\Anju;
use Tools\lib\Souhu;


/**
 * 获取最后内容
 */
class GetContent {
    // 配置文件要独立出来
    /*private static $config = [
    ];*/
    // public $configs = [];
    // function __construct(){ // 这种方式 报错 $this ,可能是类引用的问题
    //      require "config.php"；
    //     $this->configs = $config;
    // }

    public static function getConfig($name) {
        // require "config.php";  // 这样很 low 
        // var_dump($config);
        // return $config;
        // $config = new Config();
        return Config::getConfig($name);
    }

    public static function doMark($url) {
        // $config = new Config();
        // $configs = $config->getConfig();

        // var_dump($configs);die();
        // $arr = explode('/', $url);
        // $name = $arr[count($arr) - 1];
        // if($name == ''){
        //     $name = $arr[count($arr) - 2];
        // }
        // $html = file_get_contents($url); // 可以优化为专门的 curl 方法
        $html = GetHtml::getUrl($url); // 可以优化为专门的 curl 方法
        // $configs = $this->configs;

        // array_search 
        // 判断 url 选择方法
        $content = '';
        $flag = '';
        // 不同的网站 详情页不同 需要分别处理
        if (strpos($url, "leju")) {
            // 处理 url 获取详情页

            $rules = Config::getConfig('leju');
            $ret = Leju::getLeju($html,$rules);
            $flag = 'leju';
        } else if (strpos($url, "loupan")) {
            $rules = Config::getConfig('loupan');
            $ret = Loupan::getLoupan($html,$rules);
            $flag = 'loupan';
        }
        if ($ret) {
            // 存入数据库 DB 类
            // self::putContent($name, $content, $flag);
            echo ".";
            return 1;
        } else {
            echo "X";
            return 0;
        }

    }

    // 写入数据库
    private static function putContent($name, $content, $flag) {
        
    }

        // 用 sqllite 存储已抓取过的 url 
    public static function getListUrl($url){
        // 根据 url 中的关键字 判断采取何种 rule 
        // 列表 list 收藏 bookmarks 页面总结 page 

        // 还需要分页抓取 
        
        $keyword = self::getKeyWord($url);
        
        
        // $configs = new Config();
        $html = GetHtml::getUrl($url); // 获取下拉才会出现的 ajax 内容 未解决
        // 分离列表项
        // if (strpos($url, "segmentfault")) {
        //     $rules = $configs->getListConfig('segmentfault');
        // } else if (strpos($url, "tuicool")) {
        //     $rules = $configs->getListConfig('tuicool');
        // }

        $rules = Config::getListConfig($keyword);
        $prefixs = [
            'leju' => '',
            'loupan' => '',
        ];
        $prefix = $prefixs[$keyword];

        $data = QueryList::html($html)->rules($rules)->query()->getData();
        $ret = $data->all();
        // echo $html;
        // var_dump($ret);exit();
        foreach ($ret as $val) {
            $urls[] = $prefix . $val['url'];
        }
        // 写入数据库 
        return $urls;
    }

    public static function getKeyWord($url){
        $arr = ['leju', 'loupan'];
        $res = array_filter(array_map(function($val) use ($url){
                                $rr = strpos($url, $val);
                                if($rr!==false)
                                    return $rr;
                                else
                                    return false;
                            }, $arr), 
                            function($v){
                                  return $v!==false;  
                            }, 
                            ARRAY_FILTER_USE_BOTH
        );
        $ret = array_keys($res);
        $key = array_shift($ret); // 取开头第一个元素
        if($res){
            return $arr[$key];
        }
        return 0;
    }

}
