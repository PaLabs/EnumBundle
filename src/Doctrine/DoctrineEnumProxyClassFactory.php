<?php

namespace PaLabs\EnumBundle\Doctrine;


use ReflectionClass;
use Symfony\Component\Filesystem\Filesystem;

class DoctrineEnumProxyClassFactory
{
    public function createProxyClasses(string $cachePath, array $enums): array
    {
        $fs = new Filesystem();

        if (!$fs->exists($cachePath)) {
            $fs->mkdir($cachePath);
        }

        $enumClassesGenerator = new DoctrineEnumProxyClassGenerator();
        foreach ($enums as $enumClass) {
            $proxyClassName = $enumClassesGenerator->proxyClassName($enumClass);
            $proxyClassFullName = $enumClassesGenerator->proxyClassFullName($enumClass);

            if (!class_exists($proxyClassFullName, false)) {
                $cacheFile = sprintf('%s/%s.php', $cachePath, $proxyClassName);
                if (!$fs->exists($cacheFile)) {
                    $proxyCode = $enumClassesGenerator->generateProxyClass($enumClass);
                    $fs->dumpFile($cacheFile, $proxyCode);
                }
            }
        }

        $enumTypeMapping = [];
        foreach ($enums as $enumClass) {
            $enumType = (new ReflectionClass($enumClass))->getShortName();
            $proxyClassFullName = $enumClassesGenerator->proxyClassFullName($enumClass);
            $enumTypeMapping[$enumType] = $proxyClassFullName;
            $enumTypeMapping[$enumClass] = $proxyClassFullName;
        }

        return $enumTypeMapping;
    }

}