<?php


namespace PaLabs\EnumBundle\Initializer;


use PaLabs\EnumBundle\Doctrine\EnumPathScanner;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class EnumInitializerCacheWarmer implements CacheWarmerInterface
{
    public const CACHE_FILE_NAME = 'enums.php';

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

        $cachePath = $cacheDir . '/enum';
        if(!is_dir($cachePath)) {
            mkdir($cachePath);
        }

        $enumClasses = (new EnumPathScanner())->enumClassesInPaths($this->paths);
        $cacheCode = sprintf("<?php \n return %s;\n", var_export($enumClasses, true));

        $cacheFilePath = $cachePath . '/' . self::CACHE_FILE_NAME;
        file_put_contents($cacheFilePath, $cacheCode);
    }
}