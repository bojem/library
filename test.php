<?php
require_once 'vendor/autoload.php';

set_exception_handler(function ($e){
    print_r($e);exit;
});

try {
    $token = (new \q\JwtToken("1234"))
        ->withClaim("name", "1234")
        ->withClaim("age", 30)
        ->withClaim("exp", 1599663843)
        ->generateToken();

    $s = (new \q\JwtToken("1234"))->parseToken($token);


//    $s = \q\rpc\Rpc::init()->send([
//        'url' => 'http://www.money.com/api/default/test-rpc',
//        'method' => 'test',
//        'params' => [
//            'page' => 1
//        ]
//    ]);

    var_dump($s);exit;


} catch (\Exception $e){
    print_r($e);
}





