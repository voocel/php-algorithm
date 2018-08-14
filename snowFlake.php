<?php
/**
 * 生成基于雪花算法的随机编号
 * 
 * $dataCenterID 数据中心ID 0-31
 * $workerID 任务进程ID 0-31
 * $snowFlakeId 分布式ID
 */

function make($dataCenterID=0,$workerID=0){
    // 41bit timestamp + 5bit dataCenter + 5bit worker + 12bit

    $lastTimestamp = 0;
    $lastSequence  = 0;
    $sequenceMask  = 4095;
    $twepoch       = 1508945092000;

    $timestamp = timeGen();

    if ($lastTimestamp == $timestamp) {
        $lastSequence = ($lastSequence + 1) & $sequenceMask;
        if ($lastSequence == 0) $timestamp = tilNextMillis($lastTimestamp);
    } else {
        $lastSequence = 0;
    }
    $lastTimestamp = $timestamp;

    $snowFlakeId = (($timestamp - $twepoch) << 22) | ($dataCenterID << 17) | ($workerID << 12) | $lastSequence;
    return $snowFlakeId;
}

 //反向解析雪花算法生成的编号
function unmake($snowFlakeId){
    $Binary               = str_pad(decbin($snowFlakeId), 64, '0', STR_PAD_LEFT);
    $Object               = new \stdClass;
    $Object->timestamp    = bindec(substr($Binary, 0, 41)) + $twepoch;
    $Object->dataCenterID = bindec(substr($Binary, 42, 5));
    $Object->workerID     = bindec(substr($Binary, 47, 5));
    $Object->sequence     = bindec(substr($Binary, -12));
    return $Object;
}

 //等待下一毫秒的时间戳
function tilNextMillis($lastTimestamp){
    $timestamp = timeGen();
    while ($timestamp <= $lastTimestamp) {
        $timestamp = timeGen();
    }
    return $timestamp;
}

//获取毫秒级时间戳
function timeGen(){
    return (float)sprintf('%.0f', microtime(true) * 1000);
}