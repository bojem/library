<?php
namespace q\helper;
class DateHelper
{
    /**
     * 获取今日开始时间戳和结束时间戳
     *
     * 语法：mktime(hour,minute,second,month,day,year) => (小时,分钟,秒,月份,天,年)
     */
    public static function today()
    {
        return [
            'start' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
            'end' => mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1,
        ];
    }

    /**
     * 昨日
     *
     * @return array
     */
    public static function yesterday()
    {
        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')),
            'end' => mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1,
        ];
    }

    /**
     * 这周
     *
     * @return array
     */
    public static function thisWeek()
    {
        $length = 0;
        // 星期天直接返回上星期，因为计算周围 星期一到星期天，如果不想直接去掉
        if (date('w') == 0) {
            $length = 7;
        }

        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - $length, date('Y')),
            'end' => mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - $length, date('Y')),
        ];
    }

    /**
     * 上周
     *
     * @return array
     */
    public static function lastWeek()
    {
        $length = 7;
        // 星期天直接返回上星期，因为计算周围 星期一到星期天，如果不想直接去掉
        if (date('w') == 0) {
            $length = 14;
        }

        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - $length, date('Y')),
            'end' => mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - $length, date('Y')),
        ];
    }

    /**
     * 本月
     *
     * @return array
     */
    public static function thisMonth()
    {
        return [
            'start' => mktime(0, 0, 0, date('m'), 1, date('Y')),
            'end' => mktime(23, 59, 59, date('m'), date('t'), date('Y')),
        ];
    }

    /**
     * 上个月
     *
     * @return array
     */
    public static function lastMonth()
    {
        $start = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));
        $end = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));

        if (date('m', $start) != date('m', $end)) {
            $end -= 60 * 60 * 24;
        }

        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * 几个月前
     *
     * @param integer $month 月份
     * @return array
     */
    public static function monthsAgo($month)
    {
        return [
            'start' => mktime(0, 0, 0, date('m') - $month, 1, date('Y')),
            'end' => mktime(23, 59, 59, date('m') - $month, date('t'), date('Y')),
        ];
    }

    /**
     * 某年
     *
     * @param $year
     * @return array
     */
    public static function aYear($year)
    {
        $start_month = 1;
        $end_month = 12;

        $start_time = $year . '-' . $start_month . '-1 00:00:00';
        $end_month = $year . '-' . $end_month . '-1 23:59:59';
        $end_time = date('Y-m-t H:i:s', strtotime($end_month));

        return [
            'start' => strtotime($start_time),
            'end' => strtotime($end_time)
        ];
    }

    /**
     * 某月
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public static function aMonth($year = 0, $month = 0)
    {
        $year = $year ?? date('Y');
        $month = $month ?? date('m');
        $day = date('t', strtotime($year . '-' . $month));

        return [
            "start" => strtotime($year . '-' . $month),
            "end" => mktime(23, 59, 59, $month, $day, $year)
        ];
    }

    /**
     * @param int $time
     * @param string $format
     * @return mixed
     */
    public static function getWeekName(int $time, $format = "周")
    {
        $week = date('w', $time);
        $weekname = ['日', '一', '二', '三', '四', '五', '六'];
        foreach ($weekname as &$item) {
            $item = $format . $item;
        }

        return $weekname[$week];
    }

    /**
     * 格式化小时
     *
     * @param array $hours
     * @return array
     */
    public static function formatHours(array $hours)
    {
        $time = 3600 * 24;
        foreach ($hours as &$hour) {
            if ($hour == $time) {
                $hour = '24:00';
            } else {
                $hour = date('H:i', $hour + strtotime(date('Y-m-d')));
            }
        }

        return $hours;
    }

    /**
     * @param $hour
     * @return false|string
     */
    public static function formatHoursByInt($hour)
    {
        $time = 3600 * 24;
        if ($hour == $time) {
            $hour = '24:00';
        } else {
            $hour = date('H:i', $hour + strtotime(date('Y-m-d')));
        }

        return $hour;
    }

    /**
     * 格式化时间戳
     *
     * @param $time
     * @return string
     */
    public static function formatTimestamp($time)
    {
        $min = $time / 60;
        $hours = $time / 3600;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));

        return $days . " 天 " . $hours . " 小时 " . $min . " 分钟 ";
    }

    /**
     * 时间戳
     *
     * @param integer $accuracy 精度 默认微妙
     * @return int
     */
    public static function microtime($accuracy = 1000)
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * $accuracy);

        return $msectime;
    }

    /**
     * 获取今天的开始时间和结束时间
     * @return array
     */
    public static function getToDayStimeAndEtime()
    {
        $startTime = strtotime(date("Y-m-d",time()));
        $endTime = $startTime+60*60*24;
        return [
            'startTime' => $startTime,
            'endTime' => $endTime
        ];
    }

    /**
     * 获得系统某月的周数组，第一周不足的需要补足
     * @param $current_year
     * @param $current_month
     * @return array
     */
    public static function getMonthWeekArr($current_year, $current_month){
        //该月第一天
        $firstday = strtotime($current_year.'-'.$current_month.'-01');
        //该月的第一周有几天
        $firstweekday = (7 - date('N',$firstday) +1);
        //计算该月第一个周一的时间
        $starttime = $firstday-3600*24*(7-$firstweekday);
        //该月的最后一天
        $lastday = strtotime($current_year.'-'.$current_month.'-01'." +1 month -1 day");
        //该月的最后一周有几天
        $lastweekday = date('N',$lastday);
        //该月的最后一个周末的时间
        $endtime = $lastday-3600*24*$lastweekday;
        $step = 3600*24*7;//步长值
        $week_arr = array();
        for ($i=$starttime; $i<$endtime; $i= $i+3600*24*7){
            $week_arr[] = array('key'=>date('Y-m-d',$i).'|'.date('Y-m-d',$i+3600*24*6), 'val'=>date('Y-m-d',$i).'~'.date('Y-m-d',$i+3600*24*6));
        }
        return $week_arr;
    }

    /**
     * 获取本周的开始时间和结束时间
     * @param $current_time
     * @return mixed
     */
    public static function getWeekSdateAndEdate($current_time){
        $current_time = strtotime(date('Y-m-d',$current_time));
        $return_arr['sdate'] = date('Y-m-d', $current_time-86400*(date('N',$current_time) - 1));
        $return_arr['edate'] = date('Y-m-d', $current_time+86400*(7- date('N',$current_time)));
        return $return_arr;
    }

    /**
     * 获取某月的最后一天
     * @param $year
     * @param $month
     * @return false|float|int
     */
    public static function getMonthLastDay($year, $month){
        $t = mktime(0, 0, 0, $month + 1, 1, $year);
        $t = $t - 60 * 60 * 24;
        return $t;
    }

    /**
     * 返回今天-本周-本月-本年的起始时间戳
     * @return mixed
     */
    public static function getDateUnixTime(){
        $return['day'] = strtotime(date("Ymd 00:00:00"));
        $return['week'] = strtotime(date("Ymd 00:00:00", strtotime("-1 week Monday")));
        $return['month'] = strtotime(date("Y-m-01"));
        $return['year'] = strtotime(date("Y-01-01"));
        return $return;
    }
}




