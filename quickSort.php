<?php
/**
 *快速排序
 *原理:找到当前数组中的任意一个元素（一般选择第一个元素），作为标准，新建两个空数组，遍历整个数组元素，
 *如果遍历到的元素比当前的元素要小，那么就放到左边的数组，否则放到右面的数组，然后再对新数组进行同样的操作
 */

function quickSort($arr){
    if(!isset($arr[1]))       //这里是递归出口(直到数组无法拆分时)
    return $arr;

    $left = $right = array();          //定义两个空数组
    
    $tmp = $arr[0];    //获取一个用于比较的数，这里用数组的第一个
    
    foreach ($arr as $v) {
        if($v > $tmp){
            $right[] = $v;       //把大的放右边的数组
        }
        if($v < $tmp){
            $left[] = $v;        //小的放左边
        }
    }
    
    $left = quickSort($left);
    $left[] = $tmp;               //不能把这个数忘了
    $right = quickSort($right);
    //var_dump($tmp);
    return array_merge($left,$right);
}

echo "<pre>";

$arr = ['21','5','33','23','9','2','45','17'];

print_r(quickSort($arr));