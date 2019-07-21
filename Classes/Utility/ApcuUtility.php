<?php

namespace ThomasK\TkCache\Utility;

class ApcuUtility
{
    /**
     * Checks if APCu is enabled
     *
     * @return bool
     */
    public static function isApcuEnabled(): bool
    {
        return extension_loaded('apcu') && (int)ini_get('apc.enabled') === 1;
    }
}