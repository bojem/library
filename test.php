<?php
require_once 'vendor/autoload.php';

set_exception_handler(function ($e){
    print_r($e);exit;
});

try {
    $a = new \q\Captcha();
    $code = $a->code();
    $s = $a->create($code);


    echo "<img src='".$s['image']."'>";
//    print_r($s);

} catch (\Exception $e){
    print_r($e);
}





