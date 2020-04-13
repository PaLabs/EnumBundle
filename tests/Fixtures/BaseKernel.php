<?php


namespace PaLabs\EnumBundle\Test\Fixtures;


use PaLabs\EnumBundle\PaEnumBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

abstract class BaseKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new PaEnumBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('framework', [
                'secret' => 'test',
                'default_locale' => 'en',
                'translator' => [
                    'default_path' => __DIR__ . '/translations'
                ]
            ]);
        });
    }

    public function getCacheDir()
    {
        return sprintf('%s/../../var/%s/cache/%s', __DIR__, (new \ReflectionClass($this))->getShortName(), $this->environment);
    }

}