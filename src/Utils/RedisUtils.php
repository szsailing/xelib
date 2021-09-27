<?php


namespace Xiaoetech\Xelib\Utils;


use Hyperf\Utils\ApplicationContext;

class RedisUtils
{
    public static function redisResource()
    {
        $container = ApplicationContext::getContainer();
        $redis = $container->get(\Redis::class);
        return $redis;
    }
}