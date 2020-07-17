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



}




