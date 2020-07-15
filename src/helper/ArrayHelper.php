<?php
namespace q\helper;
class ArrayHelper
{
    /**
     * 递归调用 子菜单压进数组
     * Created by wqs
     * @param $menu
     * @param int $pid
     * @param string $p
     * @param string $k
     * @param string $c
     * @return array
     */
    public static function arrTree($menu, $pid=0, $p='pid', $k='id', $c='child'){
        $arr=array();
        foreach($menu as $v){
            if($v[$p]==$pid){
                $v[$c]=self::arrTree($menu,$v[$k],$p,$k,$c);
                $arr[]=$v;
            }
        }
        return $arr;
    }



}




