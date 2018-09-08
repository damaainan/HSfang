<?php 
header("Content-type:text/html; Charset=utf-8");
include("db.class.php");

// $db=new Fang();
$db=Fang::getInstance();
// $rr=$db->getLists();
// var_dump($rr);
// $ddb=clone $db;
// var_dump($ddb);

// $ss=$db->query("select * from f_list");
// var_dump($ss);
// $db->destruct();

$table="f_list";
$dataarr=[
      'url' => 'http://kangchengshiji.fang.com/',
      'name' => '康城世纪',
      'price' => '9633',
      'sale' => '111',
      'rent' => '2'
      ];

    $count=  $db->insert($table,$dataarr);
    var_dump($count);