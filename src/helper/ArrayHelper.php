<?php
namespace q\helper;
class ArrayHelper
{
    /**
     * 递归调用 子菜单压进数组
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

    /**
     * 递归数组
     *
     * @param array $items
     * @param string $idField
     * @param int $pid
     * @param string $pidField
     * @return array
     */
    public static function itemsMerge(array $items, $pid = 0, $idField = "id", $pidField = 'pid', $child = '-')
    {
        $map = [];
        $tree = [];
        foreach ($items as &$it) {
            $it[$child] = [];
            $map[$it[$idField]] = &$it;
        }

        foreach ($items as &$it) {
            $parent = &$map[$it[$pidField]];
            if ($parent) {
                $parent[$child][] = &$it;
            } else {
                $pid == $it[$pidField] && $tree[] = &$it;
            }
        }

        unset($items, $map);

        return $tree;
    }

    /**
     * 传递一个子分类ID返回所有的父级分类
     *
     * @param array $items
     * @param $id
     * @return array
     */
    public static function getParents(array $items, $id)
    {
        $arr = [];
        foreach ($items as $v) {
            if ($v['id'] == $id) {
                $arr[] = $v;
                $arr = array_merge(self::getParents($items, $v['pid']), $arr);
            }
        }

        return $arr;
    }

    /**
     * 传递一个父级分类ID返回所有子分类
     *
     * @param $cate
     * @param int $pid
     * @return array
     */
    public static function getChilds($cate, $pid)
    {
        $arr = [];
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v;
                $arr = array_merge($arr, self::getChilds($cate, $v['id']));
            }
        }

        return $arr;
    }

    /**
     * 传递一个父级分类ID返回所有子分类ID
     *
     * @param $cate
     * @param $pid
     * @param string $idField
     * @param string $pidField
     * @return array
     */
    public static function getChildIds($cate, $pid, $idField = "id", $pidField = 'pid')
    {
        $arr = [];
        foreach ($cate as $v) {
            if ($v[$pidField] == $pid) {
                $arr[] = $v[$idField];
                $arr = array_merge($arr, self::getChildIds($cate, $v[$idField], $idField, $pidField));
            }
        }

        return $arr;
    }


}




