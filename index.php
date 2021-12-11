<?php
require("pages/user/user.php");



function isMatchUrl($srcUrl, $routerPath) {

    $routerPath = str_replace("<int>", "[0-9]*", $routerPath);
    $routerPath = str_replace("<str>", ".*", $routerPath);
    $routerPath = str_replace("/", "\/", $routerPath);
    $routerPath = "/^".$routerPath."$/";

    $ret = preg_match($routerPath, $srcUrl);
    
    return $ret;

}

function getMethodParams($path, $url) {
    if(!$path) {
        return null;
    }
    if(!$url) {
        return null;
    }
    
    //$routerMapに指定したpathを分解する
    $pathTokens = preg_split("/[/]/", $path);
    //ネームスペースを取得
    $pathNameSpace = $pathTokens[0];
    //関数を取得
    $pathMethod = $pathTokens[1];
    //引数を取得
    $pathParams = array_slice($pathTokens, 2);

    //URLを分解する
    $urlTokens = preg_split("/[/]/", $url);
    $urlNameSpace = $urlTokens[0];
    $urlMethod = $urlTokens[1];
    $urlParams = array_slice($urlTokens, 2);

    if(count($pathParams) != count($urlParams)) {
        //パラメーターの数が異なる
        return null;
    }

    for($i = 0; $i < count($pathParams); $i++) {
        switch($pathParams[$i]) {
            case "<int>":
                if(!is_numeric($urlParams[$i])) {
                    return null;
                }
                break;
            default:
                break;
        }
    }
    //OKなパラメーターだった場合。
    return $urlParams;
}

function main() {
//URLのパスと呼び出す関数を設定する
    $routerMap = [
        ["path"=>"/", "func"=>'user\index', "auth"=>false, "method"=>"GET"],
        ["path"=>"users", "func"=>'user\index', "auth"=>false, "method"=>"POST"],
        ["path"=>"users/post/<int>/<str>", "func"=>'user\post', "auth"=>false, "method"=>"POST"],
    ];

    $url = "/";
    if(array_key_exists('url', $_GET)) {
        $url = $_GET['url'];
    }
    print_r($_GET);
    print_r($_POST);


    foreach($routerMap as $map) {
        $path = $map["path"];
        $func = $map["func"];
        $isAuth = $map["auth"];

        if(isMatchUrl($url, $path)==1) {
            print($url."と".$path."がマッチしました\n");
            //$func();
        }
    }
}

main();

?>