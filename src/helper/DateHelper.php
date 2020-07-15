<?php
namespace q\helper;
class DateHelper
{
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
     * Created by wqs
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




