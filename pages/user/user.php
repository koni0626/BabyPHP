<?php
namespace user;


function index() {
    $params = [
        "title"=>"これはタイトルです",
        "message"=>"これはメッセージです"
    ];

    //ここでjsonで返せばwebapi、requireで返せばMVCになる感じか？
    require("./pages/user/view.php");

}

function post($param1, $param2) {
    print("postされました");
}