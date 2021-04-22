<?php
namespace q\helper;

class StringHelper
{
    /**
     * 生成随机字符串
     * @return string
     */
    public static function uuidRandStr()
    {
        return sha1(self::uuid() . microtime(true) . mt_rand(1, 99999999));
    }

    /**
     * 生成随机数
     * @param int|int $length
     * @return int
     */
    public static function generateCode(int $length = 4): int
    {
        return rand(pow(10, ($length - 1)), pow(10, $length) - 1);
    }

    /**
     * 获取随机字符串
     * @param int $len 字符串长度
     * @param bool $hasSpecial 是否有特殊字符
     * @return string
     */
    public static function randStr(int $len = 6, bool $hasSpecial = false): string {
        $chars = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        if ($hasSpecial) {
            $chars .= '!@#$%^&*()_+-=`~[]{}|<>?:';
        }
        $result = '';
        $max    = strlen($chars) - 1;
        for ($i = 0; $i < $len; $i++) {
            $result .= $chars[rand(0, $max)];
        }
        return $result;
    }

    /**
     * 获取随机字符串
     *
     * @param $length
     * @param bool $numeric
     * @return string
     */
    public static function randStr1($length, $numeric = false)
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));

        $hash = '';
        if (!$numeric) {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }

        $max = strlen($seed) - 1;
        $seed = str_split($seed);
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed[mt_rand(0, $max)];
        }

        return $hash;
    }



    /**
     * 生成随机数
     * @param  integer  $length  长度
     * @param  integer $numeric 是否包含字母
     * @return string           随机数
     */
    public static function random($length, $numeric = 0) {
        PHP_VERSION < '4.2.0' ? mt_srand((double) microtime() * 1000000) : mt_srand();
        $seed = base_convert(md5(print_r($_SERVER, 1) . microtime()), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        $hash = '';
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i ++) {
            $hash .= $seed[mt_rand(0, $max)];
        }
        return $hash;
    }

    /**
     * 获取数字随机字符串
     *
     * @param bool $prefix 判断是否需求前缀
     * @param int $length 长度
     * @return string
     */
    public static function random1($prefix = false, $length = 8)
    {
        $str = $prefix ?? '';

        return $str . substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, $length);
    }

    /**
     * 生成随机数
     *
     * @param $merchant_id
     * @return false|string
     */
    public static function random2($merchant_id)
    {
        $time_str = date('YmdHis');
        $rand_code = rand(0, 999999);

        return substr(md5($time_str . $rand_code . $merchant_id), 16, 32);
    }

    /**
     * 读取/dev/urandom生成随机数
     * @param int $len
     * @return false|string
     */
    public static function randomFromDev(int $len) {
        $fp = @fopen('/dev/urandom','rb');
        $result = '';
        if ($fp !== FALSE) {
            $result .= @fread($fp, $len);
            @fclose($fp);
        }else{
            return self::randomFromDev($len);
        }
        $result = base64_encode($result);
        $result = strtr($result, '+/', '-_');
        return substr($result, 0, $len);
    }

    /**
     * 生成一个订单sn
     * Created by wqs
     * @param int $length
     * @param string $prev
     * @param bool $timeStatus
     * @return string
     */
    public static function createOrdersSn(int $length = 6, $prev = 'CP', $timeStatus = true): string
    {
        $sTime = date('YmdHis');
        $rand = self::generateCode($length);
        if ($timeStatus) {
            $result = $prev . $sTime . $rand;
        } else {
            $result = $prev . $rand;
        }
        return $result;
    }

    /**
     * 生成UUID
     * @return string
     */
    public static function uuid(): string
    {
        if (function_exists('uuid_create')) {
            return uuid_create();
        } else {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
            $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
            return $uuid;
        }
    }

    /**
     * 截取字符串
     * @param  string   $string
     * @param  int      $start
     * @param  int|null $length
     * @return string
     */
    public static function substr(string $string, int $start, int $length = null): string
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }

    /**
     * 截取字符串
     * @param $str
     * @param int $start
     * @param $length
     * @param string $charset
     * @param bool $suffix
     * @return false|string
     */
    public static function mbSubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
    {
        if(function_exists("mb_substr")){
            if($suffix)
                return mb_substr($str, $start, $length, $charset)."...";
            else
                return mb_substr($str, $start, $length, $charset);
        }
        elseif(function_exists('iconv_substr')) {
            if($suffix)
                return iconv_substr($str,$start,$length,$charset)."...";
            else
                return iconv_substr($str,$start,$length,$charset);
        }
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
        if($suffix) return $slice."…";
        return $slice;
    }

    /**
     * 隐藏真实名称(如姓名、账号、公司等)
     * @param string $str
     * @return string
     */
    public static function hideTrueName(string $str): string
    {
        $res = '**';
        if ($str != '') {
            $len = mb_strlen($str, 'UTF-8');
            if ($len <= 3) {
                $res = mb_substr($str, 0, 1, 'UTF-8') . $res;
            } elseif ($len < 5) {
                $res = mb_substr($str, 0, 2, 'UTF-8') . $res;
            } elseif ($len < 10) {
                $res = mb_substr($str, 0, 2, 'UTF-8') . '***' . mb_substr($str, ($len - 2), $len, 'UTF-8');
            } elseif ($len < 16) {
                $res = mb_substr($str, 0, 3, 'UTF-8') . '***' . mb_substr($str, ($len - 3), $len, 'UTF-8');
            } else {
                $res = mb_substr($str, 0, 4, 'UTF-8') . '***' . mb_substr($str, ($len - 4), $len, 'UTF-8');
            }
        }
        return $res;
    }

    /**
     * 加密
     * @param $txt
     * @param string $key
     * @return string
     */
    public static function encrypt($txt,$key='str')
    {
        $txt = $txt.$key;
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $nh = rand(0,64);
        $ch = $chars[$nh];
        $mdKey = md5($key.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt = base64_encode($txt);
        $tmp = '';
        $i=0;$j=0;$k = 0;
        for ($i=0; $i<strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = ($nh+strpos($chars,$txt[$i])+ord($mdKey[$k++]))%64;
            $tmp .= $chars[$j];
        }
        return urlencode(base64_encode($ch.$tmp));
    }

    /**
     * 解密
     * @param $txt
     * @param string $key
     * @return string
     */
    public static function decrypt($txt,$key='str')
    {
        $txt = base64_decode(urldecode($txt));
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $ch = $txt[0];
        $nh = strpos($chars,$ch);
        $mdKey = md5($key.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt = substr($txt,1);
        $tmp = '';
        $i=0;$j=0; $k = 0;
        for ($i=0; $i<strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = strpos($chars,$txt[$i])-$nh - ord($mdKey[$k++]);
            while ($j<0) $j+=64;
            $tmp .= $chars[$j];
        }
        return trim(base64_decode($tmp),$key);
    }

    /**
     * 获取用户真实ip
     * @return string
     */
    public static function getUserIp()
    {
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '127.0.0.1';
        }
        return $ip;
    }

    /**
     * get方式请求
     * @param $url
     * @return bool|string
     */
    public static function httpGet($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        $otatus = curl_getinfo($curl);
        curl_close($curl);
        if (intval($otatus["http_code"]) == 200) {
            return $output;
        }
        return false;
    }

    /**
     * post方式请求
     * @param $url
     * @param $param
     * @param bool $post_file
     * @return bool|string
     */
    public static function httpPost($url, $param, $post_file = false)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach ($param as $key => $val) {

                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    /**
     * 判断是否为https
     * @return bool 是https返回true;否则返回false
     */
    public static function isHttps() {
        if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
            return true;
        } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 省略文字
     *
     * @param $text
     * @param int $num
     * @return string|string[]
     */
    public static function textOmit($string, $num = 26)
    {
        $letter = [];
        for ($i = 0; $i < mb_strlen($string, 'UTF-8'); $i++) {
            $letter[] = mb_substr($string, $i, 1, 'UTF-8');
        }

        $content = "";
        foreach ($letter as $key => $l) {
            if ($key + 1 == $num) {
                $content .= '...';
                break;
            }

            $content .= $l;
        }

        return $content;
    }

    /**
     * 将一个字符串部分字符用*替代隐藏
     *
     * @param string $string 待转换的字符串
     * @param int $bengin 起始位置，从0开始计数，当$type=4时，表示左侧保留长度
     * @param int $len 需要转换成*的字符个数，当$type=4时，表示右侧保留长度
     * @param int $type 转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串
     * @param string $glue 分割符
     * @return bool|string
     */
    public static function hideStr($string, $bengin = 0, $len = 4, $type = 0, $glue = "@")
    {
        if (empty($string)) {
            return false;
        }

        $array = [];
        if ($type == 0 || $type == 1 || $type == 4) {
            $strlen = $length = mb_strlen($string);

            while ($strlen) {
                $array[] = mb_substr($string, 0, 1, "utf8");
                $string = mb_substr($string, 1, $strlen, "utf8");
                $strlen = mb_strlen($string);
            }
        }

        switch ($type) {
            case 0 :
                for ($i = $bengin; $i < ($bengin + $len); $i++) {
                    isset($array[$i]) && $array[$i] = "*";
                }

                $string = implode("", $array);
                break;
            case 1 :
                $array = array_reverse($array);
                for ($i = $bengin; $i < ($bengin + $len); $i++) {
                    isset($array[$i]) && $array[$i] = "*";
                }

                $string = implode("", array_reverse($array));
                break;
            case 2 :
                $array = explode($glue, $string);
                $array[0] = self::hideStr($array[0], $bengin, $len, 1);
                $string = implode($glue, $array);
                break;
            case 3 :
                $array = explode($glue, $string);
                $array[1] = self::hideStr($array[1], $bengin, $len, 0);
                $string = implode($glue, $array);
                break;
            case 4 :
                $left = $bengin;
                $right = $len;
                $tem = array();
                for ($i = 0; $i < ($length - $right); $i++) {
                    if (isset($array[$i])) {
                        $tem[] = $i >= $left ? "*" : $array[$i];
                    }
                }

                $array = array_chunk(array_reverse($array), $right);
                $array = array_reverse($array[0]);
                for ($i = 0; $i < $right; $i++) {
                    $tem[] = $array[$i];
                }
                $string = implode("", $tem);
                break;
        }

        return $string;
    }

    /**
     * php截取指定两个字符之间字符串，默认字符集为utf-8 Power by 大耳朵图图
     * @param string $begin  开始字符串
     * @param string $end    结束字符串
     * @param string $str    需要截取的字符串
     * @return string
     */
    public static function cut($begin,$end,$str){
        $b = mb_strpos($str,$begin) + mb_strlen($begin);
        $e = mb_strpos($str,$end) - $b;

        return mb_substr($str,$b,$e);
    }

    /**
     *  根据身份证号码获取性别
     *  author:xiaochuan
     *  @param string $idcard    身份证号码
     *  @return int $sex 性别 1男 2女 0未知
     */
    public static function get_sex($idcard) {
        if(empty($idcard)) return null;
        $sexint = (int) substr($idcard, 16, 1);
        return $sexint;
    }

    /**
     *  根据身份证号码获取生日
     *  author:xiaochuan
     *  @param string $idcard    身份证号码
     *  @return $birthday
     */
    public static function get_birthday($idcard) {
        if(empty($idcard)) return null;
        $bir = substr($idcard, 6, 8);
        $year = (int) substr($bir, 0, 4);
        $month = (int) substr($bir, 4, 2);
        $day = (int) substr($bir, 6, 2);
        return $year . "-" . $month . "-" . $day;
    }

    /**
     *  根据身份证号码计算年龄
     *  author:xiaochuan
     *  @param string $idcard    身份证号码
     *  @return int $age
     */
    public static function get_age($idcard){
        if(empty($idcard)) return null;
        #  获得出生年月日的时间戳
        $date = strtotime(substr($idcard,6,8));
        #  获得今日的时间戳
        $today = strtotime('today');
        #  得到两个日期相差的大体年数
        $diff = floor(($today-$date)/86400/365);
        #  strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
        $age = strtotime(substr($idcard,6,8).' +'.$diff.'years')>$today?($diff+1):$diff;
        return $age;
    }

    /**
     *  判断字符串是否是身份证号
     *  author:xiaochuan
     *  @param string $idcard    身份证号码
     */
    public static function isIdCard($idcard){
        #  转化为大写，如出现x
        $idcard = strtoupper($idcard);
        #  加权因子
        $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        #  按顺序循环处理前17位
        $sigma = 0;
        #  提取前17位的其中一位，并将变量类型转为实数
        for ($i = 0; $i < 17; $i++) {
            $b = (int)$idcard{$i};
            #  提取相应的加权因子
            $w = $wi[$i];
            #  把从身份证号码中提取的一位数字和加权因子相乘，并累加
            $sigma += $b * $w;
        }
        #  计算序号
        $sidcard = $sigma % 11;
        #  按照序号从校验码串中提取相应的字符。
        $check_idcard = $ai[$sidcard];
        if ($idcard{17} == $check_idcard) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  根据身份证号，返回对应的生肖
     *  author:xiaochuan
     *  @param string $idcard    身份证号码
     */
    public static function get_zodiac($idcard){ //
        if(empty($idcard)) return null;
        $start = 1901;
        $end = (int)substr($idcard, 6, 4);
        $x = ($start - $end) % 12;
        $val = '';
        if ($x == 1 || $x == -11) $val = '鼠';
        if ($x == 0)              $val = '牛';
        if ($x == 11 || $x == -1) $val = '虎';
        if ($x == 10 || $x == -2) $val = '兔';
        if ($x == 9 || $x == -3)  $val = '龙';
        if ($x == 8 || $x == -4)  $val = '蛇';
        if ($x == 7 || $x == -5)  $val = '马';
        if ($x == 6 || $x == -6)  $val = '羊';
        if ($x == 5 || $x == -7)  $val = '猴';
        if ($x == 4 || $x == -8)  $val = '鸡';
        if ($x == 3 || $x == -9)  $val = '狗';
        if ($x == 2 || $x == -10) $val = '猪';
        return $val;
    }

    /**
     *  根据身份证号，返回对应的星座
     *  author:xiaochuan
     *  @param string $idcard    身份证号码
     */
    public static function get_starsign($idcard){
        if(empty($idcard)) return null;
        $b = substr($idcard, 10, 4);
        $m = (int)substr($b, 0, 2);
        $d = (int)substr($b, 2);
        $val = '';
        if(($m == 1 && $d <= 21) || ($m == 2 && $d <= 19)){
            $val = "水瓶座";
        }else if (($m == 2 && $d > 20) || ($m == 3 && $d <= 20)){
            $val = "双鱼座";
        }else if (($m == 3 && $d > 20) || ($m == 4 && $d <= 20)){
            $val = "白羊座";
        }else if (($m == 4 && $d > 20) || ($m == 5 && $d <= 21)){
            $val = "金牛座";
        }else if (($m == 5 && $d > 21) || ($m == 6 && $d <= 21)){
            $val = "双子座";
        }else if (($m == 6 && $d > 21) || ($m == 7 && $d <= 22)){
            $val = "巨蟹座";
        }else if (($m == 7 && $d > 22) || ($m == 8 && $d <= 23)){
            $val = "狮子座";
        }else if (($m == 8 && $d > 23) || ($m == 9 && $d <= 23)){
            $val = "处女座";
        }else if (($m == 9 && $d > 23) || ($m == 10 && $d <= 23)){
            $val = "天秤座";
        }else if (($m == 10 && $d > 23) || ($m == 11 && $d <= 22)){
            $val = "天蝎座";
        }else if (($m == 11 && $d > 22) || ($m == 12 && $d <= 21)){
            $val = "射手座";
        }else if (($m == 12 && $d > 21) || ($m == 1 && $d <= 20)){
            $val = "魔羯座";
        }
        return $val;
    }
}