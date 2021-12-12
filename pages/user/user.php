<?php
namespace user;


function index($db) {
    $params = [
        "title"=>"これはタイトルです",
        "message"=>"これはメッセージです"
    ];

    $result = $db->query("select * from users");

    //連想配列を取得
    $row = $result->fetch_assoc();
    print_r($row);

    //ここでjsonで返せばwebapi、requireで返せばMVCになる感じか？
    require("./pages/user/view.php");

}

function post($db, $param1, $param2) {


    print("postされました<br>");
    print($param1."\n");
    print($param2."\n");
    
}