<?php


namespace PaLabs\EnumBundle\Doctrine;


use PaLabs\Enum\Enum;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class EnumPathScanner
{

    public function enumClassesInPaths(array $paths): array {
        $this->requireFilesInPaths($paths);

        $declaredClasses = get_declared_classes();
        $enumClasses = array_filter($declaredClasses,
            fn($className) => is_subclass_of($className, Enum::class)
        );

        return $enumClasses;
    }

    private function requireFilesInPaths(array $paths): void
    {
        foreach ($paths as $path) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($iterator as $file) {
                if ($file->getBasename('.php') === $file->getBasename()) {
                    continue;
                }

                $sourceFile = realpath($file->getPathName());
                /** @noinspection PhpIncludeInspection */
                require_once $sourceFile;
            }
        }
    }
}