<?php
namespace q\helper;
class ConvertHelper
{
    /**
     * 格式化显示金额 分->元
     *
     * @param int $value
     * @param bool $format
     * @return float|string
     */
    public static function moneyToShow($value = 0, $format = true)
    {
        $value = floatval($value) * 0.01;
        return $format ? number_format($value, 2, '.', '') : $value;
    }

    /**
     * 格式化金额 元->分
     *
     * @param int $value
     * @return int
     */
    public static function moneyToSave($value = 0): int
    {
        return is_numeric($value) ? intval(strval($value * 100)) : 0;
    }

    /**
     * 格式化字节大小
     *
     * @param int    $size 字节数
     * @param string $delimiter 数字和单位分隔符
     * @return string            格式化后的带单位的大小
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function formatBytes($size, $delimiter = ''){
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
        return round($size, 2).$delimiter.$units[$i];
    }

    /**
     * 人性化数字
     *
     * @param int $num
     * @return string
     */
    public static function formatSimple($num){
        if($num < 1000){
            return $num;
        }

        if($num < 10000){
            return round($num / 1000, 2)."千";
        }

        if($num < 100000000){
            return round($num / 10000, 2)."万";
        }

        return round($num / 100000000, 2)."亿";
    }

    /**
     * 计算两点地理坐标之间的距离
     *
     * @param float $longitude1 起点经度
     * @param float $latitude1 起点纬度
     * @param float $longitude2 终点经度
     * @param float $latitude2 终点纬度
     * @param int   $unit 单位 1:米 2:公里
     * @param int   $decimal 精度 保留小数位数
     * @return float
     */
    public static function calcDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit = 2, $decimal = 2){
        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI = 3.1415926;

        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;

        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI / 180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $distance = $distance * $EARTH_RADIUS * 1000;

        if($unit == 2){
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }
}




