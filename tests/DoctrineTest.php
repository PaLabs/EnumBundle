<?php


namespace PaLabs\EnumBundle\Test;


use PaLabs\EnumBundle\PaEnumBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class DoctrineTest extends KernelTestCase
{
    protected static $class = DoctrineTestKernel::class;


    public function testTypesGeneration()
    {
         $this->assertTrue(class_exists('\Sciact\Enum\Doctrine\ActionEnumDoctrineEnum'));
         $this->assertTrue(class_exists('\Sciact\Enum\Doctrine\BookTypeEnumDoctrineEnum'));
    }

    protected function setUp(): void
    {
        self::bootKernel();
    }

}

class DoctrineTestKernel extends Kernel
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
            ]);
            $container->loadFromExtension('pa_enum', [
                'doctrine' => [
                    'path' => [
                        __DIR__ . '/Fixtures'
                    ],
                ],
            ]);
        });
    }

    public function getCacheDir()
    {
        return sprintf('%s/../var/%s/cache/%s', __DIR__, (new \ReflectionClass($this))->getShortName(), $this->environment);
    }

}