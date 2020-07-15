<?php
require_once 'vendor/autoload.php';

set_exception_handler(function ($e){
    print_r($e);exit;
});

try {
    $s = \q\helper\DateHelper::getMonthWeekArr();
    print_r($s);

//    $url = 'https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIoDiacRrdhTCW5kxkGXPeOphj7atp6XUbFiczT1WStzHwjfsgbh8JUxAPoWlYDCGMIibw2MzTG5EK9g/132';
//    \q\FileHelper::downloadWechatHead($url, './avatar/' , 'user_id');
//    \q\Image::open('./avatar/20200525134226_387.jpg')->radius(50)->save('./avatar/user_id2.jpeg');
} catch (Exception $e){
    print_r($e);
}





