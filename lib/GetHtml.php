<?php
namespace Tools;

require "../vendor/autoload.php";

class GetHtml {
    public static function getUrl($url) {
        if (strpos($url, 'leju')) {
            $cookie = '';
            $header = "http://house.leju.com/";
            // $ret = self::curlHttps($url, $cookie, $header);
            $ret = self::curlHttps($url, $header);
            return $ret;
        } else if (strpos($url, 'loupan')) {
            $cookie = "";
            $header = "http://hs.loupan.com";
            $ret = self::curlHttps($url, $cookie, $header);
            return $ret;
        }  else {
            $html = file_get_contents($url);
            return $html;
        }
    }

    private static function curlHttps($url, $header, $cookie = '') {
        $ch = curl_init();
        // 2. 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0); //https://www.tuicool.com/topics
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3322.4 Safari/537.36");
        curl_setopt($ch, CURLOPT_REFERER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        // curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}