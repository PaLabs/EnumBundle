<?php


namespace PaLabs\EnumBundle\Test;


use PaLabs\EnumBundle\PaEnumBundle;
use PaLabs\EnumBundle\Test\Fixtures\BaseKernel;
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

class DoctrineTestKernel extends BaseKernel
{
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        parent::registerContainerConfiguration($loader);
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('pa_enum', [
                'doctrine' => [
                    'path' => [
                        __DIR__ . '/Fixtures'
                    ],
                ],
            ]);
        });
    }
}