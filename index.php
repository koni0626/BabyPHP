<?php
require("pages/user/user.php");


/**
 * 送信元のクエリー(URL)とルーティングのURLを正規表現で比較する。
 * @param string $queryString　
 * @param string $routerPath <int>,<str>を含むルーティングのパス
 * @return $srcUrlと$routerPathが一致する場合1, 異なる場合0を返す
 */
function isMatchUrl($srcUrl, $routerPath) {
    $routerPath = str_replace("<int>", "[0-9]*", $routerPath);
    $routerPath = str_replace("<str>", ".*", $routerPath);
    $routerPath = str_replace("/", "\/", $routerPath);
    $routerPath = "/^".$routerPath."$/";

    return preg_match($routerPath, $srcUrl);


}

/**
 * 送信元URLを分解し、パラメーターを取得する。
 * URLは以下の形式とし、param1以降を分解して取得する
 * /page/action/param1/param2/…
 * @param string $queryString URL
 * @return パラメーターを先頭から配列形式で返却する
 */
function getUrlParams($queryString) {
    $urlParams = null;
    //URLを分解する
    $urlTokens =explode("/", $queryString);
    $tokenNum = count($urlTokens);
     if($tokenNum > 2) {
        $urlParams = array_slice($urlTokens, 2);
    }

    return $urlParams;
}

/**
 * メソッド名を大文字、小文字を区別せずに比較する
 * @param string $srcMethod 比較元メソッド名
 * @param string $dstMethod 比較先メソッド名
 * @return 一致する場合True、異なる場合False
 */
function isMatchMethod($srcMethod, $dstMethod) {
    $ret = false;
    if(!$srcMethod) {
        return false;
    }

    if(!$dstMethod) {
        return false;
    }

    if(strcasecmp($srcMethod, $dstMethod) == 0) {
        $ret = true;
    }

    return $ret;
}

function defaultAuth($db, $session) {

}

function middleWare($db, $queryString) {

}

/**
 * フレームワークのエントリポイント。
 * アクセスされたURLを各処理に振り分ける。
 */
function routerMain() {
    $db = null;
    $requestMethod = "";
    $queryString = "/";
    //URLのパスと呼び出す関数を設定する
    $routerMap = [
        ["path"=>"users", "func"=>'user\get', "auth"=>false, "method"=>"GET"],
        ["path"=>"users/post/<int>/<str>", "func"=>'user\post', "auth"=>false, "method"=>"POST"],
    ];

    //メソッドの種別を取得する
    if(isset($_SERVER["REQUEST_METHOD"])) {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
    }
    else {
        //パラメーター不正
        return;
    }
    print_r($_SERVER["QUERY_STRING"]);
    //クエリーを取得する
    if(isset($_SERVER["QUERY_STRING"])) {
        $tokens = explode("=", $_SERVER["QUERY_STRING"]);
        if(count($tokens) == 2) {
            $queryString = $tokens[1];
        }
    }
    else {
        //パラメーター不正
        return;
    }
    
    //データベースの接続
    $db= new mysqli("localhost", "root", "", "testdb");
    if($db->connect_error) {
        print("接続エラー");
        return;
    }
    else {
        $db->set_charset('utf8');
    }



   // print_r($_GET);
   // print_r($_POST);
   // print_r($_SERVER);

    //クエリーから呼び出す関数を検索する
    foreach($routerMap as $map) {
        $path = $map["path"];
        $callBack = $map["func"];
        $isAuth = $map["auth"];
        $method = $map["method"];
        //print($queryString."と".$path."の比較<br>");
        if(isMatchUrl($queryString, $path) && isMatchMethod($requestMethod, $method)) {
          
            //TODO 認証処理を追加する
            if(!defaultAuth($db, null)) {
                //認証エラー
            }
            
            //ここでミドルウェアを実装する。
             middleWare($db, $queryString);

            $params = [$db];
            $params[] = getUrlParams($queryString);
            //関数呼び出し
            call_user_func_array($callBack, $params);
            
            break;
        }
    }

    if($db) {
        $db->close();
    }
}

//エントリポイントの起動
routerMain();

?>