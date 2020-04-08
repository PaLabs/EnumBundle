<?php


namespace PaLabs\EnumBundle\Doctrine;


use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class DoctrineEnumClassCacheWarmer implements CacheWarmerInterface
{
    private array $paths;

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function isOptional()
    {
        return false;
    }

    public function warmUp(string $cacheDir)
    {
        if(empty($this->paths)) {
            return;
        }

        $cachePath = $cacheDir . '/enum/doctrine';
        $enumClasses = (new EnumPathScanner())->enumClassesInPaths($this->paths);
        (new DoctrineEnumProxyClassFactory())->createProxyClasses($cachePath, $enumClasses);

        (new DoctrineEnumCacheLoader())->loadCache($cacheDir);
    }
}