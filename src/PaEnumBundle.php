<?php


namespace PaLabs\EnumBundle;


use PaLabs\EnumBundle\DependencyInjection\PaEnumExtension;
use PaLabs\EnumBundle\Doctrine\DoctrineEnumCacheLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PaEnumBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new PaEnumExtension();
    }

    public function boot()
    {
        $cacheDir = $this->container->getParameter('kernel.cache_dir');
        (new DoctrineEnumCacheLoader())->loadCache($cacheDir);
    }


}