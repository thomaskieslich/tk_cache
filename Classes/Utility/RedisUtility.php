<?php

namespace ThomasK\TkCache\Utility;

class RedisUtility
{
    /**
     * Checks if APCu is enabled
     *
     * @return bool
     */
    public static function isRedisEnabled(): bool
    {
        return extension_loaded('redis');
    }
}