<?php


namespace PaLabs\EnumBundle\Initializer;


use PaLabs\Enum\Enum;

class EnumInitializer
{

    public function init(string $cacheDir): void {
        $cacheFile = $cacheDir . '/enum/' . EnumInitializerCacheWarmer::CACHE_FILE_NAME;

        if(!file_exists($cacheFile)) {
            return;
        }
        $enumClasses = require $cacheFile;

        /** @var Enum $enumClass */
        foreach ($enumClasses as $enumClass) {
            $enumClass::init();
        }
    }
}