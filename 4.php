<?php 
// 处理详情
header('Content-Type:text/html; charset=utf-8');
include("db.class.php");

// include("phpQuery/phpQuery.php");

require 'vendor/autoload.php';
use QL\QueryList;



set_time_limit(0);

function openUrl($url){
    $ch = curl_init();
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
}//CURLOPT_REFERER

function getUrl(){
    $db=Fang::getInstance();
    //从数据库里获取未抓取的详情页链接
    $sql="select id,name,url from f_list ";
    $result =  $db->query($sql);
    return $result;
}


function getData($url){
    // $url="http://wanheruijing.fang.com/xiangqing/";
    $url=$url."xiangqing/";
    $html=openUrl($url);


    $arrstr = explode('<body', $html);
    // var_dump($result);
    $result = explode('</body>', $arrstr[1]);
    $str = $result[0];
    $str=preg_replace('/<script(.*?)<\/script>/','' ,$str);
    $str=preg_replace('/<script (.*?)<\/script>/','' ,$str);
    $str=iconv("GB2312","UTF-8//IGNORE",$str);

    // echo $str;


    $rules = array(
        //采集class为two下面的超链接的链接
        'link' => array('dl','html')
    );
    $data = QueryList::Query($str,$rules,'.box:eq(1) .inforwrap',null,null)->data;
    // var_dump($data);exit;
    $sss = $data[0]['link'];


    // 利用 phpquery 实例化 ，判断是否包含字符串，获取相应位置数据

    /* 使用 composer 引入的 phpquery  */

    phpQuery::newDocument($sss);  //实例化   获取的每个列表页
    $lists=pq("dd");
    $detail=[];
    foreach ($lists as $ke => $va) {
        $doc=pq($va)->text();
        // echo $doc;exit;
        $value=explode("：",$doc);
        $value=$value[1];
        if(preg_match("/建筑面积/",$doc)>0){
            $detail['t_area'] = intval($value);
        }elseif(preg_match("/占地面积/",$doc)>0){
            $detail['area'] = intval($value);
        }elseif(preg_match("/当期户数/",$doc)>0){
            $detail['now_house'] = intval($value);
        }elseif(preg_match("/总 户 数/",$doc)>0){
            $detail['t_house'] = intval($value);
        }elseif(preg_match("/绿 化 率/",$doc)>0){
            $detail['green_radio'] = $value;
        }elseif(preg_match("/容/",$doc)>0){
            $detail['plot_radio'] = floatval($value);
        }elseif(preg_match("/竣工时间/",$doc)>0){
            $detail['finish_time'] = $value;
        }elseif(preg_match("/开 发 商/",$doc)>0){
            $detail['developer'] = $value;
        }

        // echo $value,"<br/>";
    }
    
    // var_dump($detail);
    return $detail;
}

function Storage($data){
    $db=Fang::getInstance();
    $table="f_detail";
    $count=  $db->insert($table,$data);
    echo $count,"<br/>";

}

$resu=getUrl();
// var_dump($resu);
foreach ($resu as $val) {
    $url=$val['url'];
    $id=$val['id'];
    $name=$val['name'];
    // var_dump($url);
    $data=getData($url);
    if(null==$data){
        echo 2222,"<br/>";
    }else{
        // var_dump($data);
        $arr=array_values($data);
        // var_dump($arr);
        $arr=$data;
        $arr['zone']='桃城区';
        $arr['lid']=$id;
        $arr['name']=$name;
        // var_dump($arr);
        Storage($arr);
    }
}
