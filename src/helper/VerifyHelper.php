<?php
namespace q\helper;
class VerifyHelper
{
    /**
     * 判断是否为手机号
     * @param  String  $numbers [号码]
     * @return boolean          [bool]
     */
    public static function isMobile(String $numbers): bool
    {
        $pattern = "/^((13[0-9])|(14[5,7,9])|(15[^4])|(17[0,1,3,5,6,7,8])|(18[0-9])|(19[8,9]))\d{8}$/";
        if(preg_match($pattern, $numbers))
            return true;
        else
            return false;
    }
}




