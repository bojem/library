<?php
require_once 'vendor/autoload.php';

set_exception_handler(function ($e){
    print_r($e);exit;
});

try {
    $s = \q\rpc\Rpc::init()->send([
        'url' => 'http://www.money.com/api/default/test-rpc',
        'method' => 'test',
        'params' => [
            'page' => 1
        ]
    ]);

    print_r($s);exit;


} catch (\Exception $e){
    print_r($e);
}





