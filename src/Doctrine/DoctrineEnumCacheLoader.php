<?php


namespace PaLabs\EnumBundle\Doctrine;


use PaLabs\Enum\Enum;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DoctrineEnumCacheLoader
{
    public function loadCache(string $cacheDir): void {
        $cachePath = $cacheDir . '/enum/doctrine';
        if(!is_dir($cachePath)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($cachePath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if ($file->getBasename('.php') == $file->getBasename()) {
                continue;
            }

            $sourceFile = realpath($file->getPathName());
            /** @noinspection PhpIncludeInspection */
            require_once $sourceFile;
        }
    }
}