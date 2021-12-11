<?php
    $routerMap = [
        ["path"=>"/", "func"=>'user\index', "auth"=>false, "method"=>"GET"],
        ["path"=>"users", "func"=>'user\index', "auth"=>false, "method"=>"POST"],
        ["path"=>"users/<int>/<text>", "func"=>'user\post', "auth"=>false, "method"=>"POST"],
    ];

    $srcUrl = "users/65/aaa";
    foreach($routerMap as $router) {
        $routerPath = $router["path"];

        $routerPath = str_replace("<int>", "[0-9]*", $routerPath);
        $routerPath = str_replace("<text>", ".*", $routerPath);
        $routerPath = str_replace("/", "\/", $routerPath);
        $routerPath = "/^".$routerPath."$/";
        print_r($routerPath."\n");
        $ret = preg_match($routerPath, $srcUrl, $matches);
        var_dump($matches);
        print_r($ret."\n");
    }
