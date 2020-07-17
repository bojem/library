<?php
require_once 'vendor/autoload.php';

set_exception_handler(function ($e){
    print_r($e);exit;
});

try {
    $url = 'https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIoDiacRrdhTCW5kxkGXPeOphj7atp6XUbFiczT1WStzHwjfsgbh8JUxAPoWlYDCGMIibw2MzTG5EK9g/132';
    $localUrl = \q\helper\FileHelper::downloadWechatHead($url, './', 'avatar');
    //头像圆角处理
    \q\Image::open($localUrl)->radius(50)->save($localUrl);
    \q\Image::open('./back.jpg')->water($localUrl, \q\Image::WATER_CENTER)->save("./2.png");




} catch (Exception $e){
    print_r($e);
}





