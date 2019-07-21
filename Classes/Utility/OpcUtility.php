<?php

namespace ThomasK\TkCache\Utility;

class OpcUtility
{
    /**
     * Checks if Opcache is enabled
     *
     * @return bool
     */
    public static function isOpcacheEnabled(): bool
    {
        return (int)ini_get('opcache.enable') === 1;
    }
}