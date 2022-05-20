<?php


namespace PaLabs\EnumBundle\Test;


use PaLabs\EnumBundle\Test\Fixtures\BaseKernel;
use PaLabs\EnumBundle\Test\Fixtures\EnumWithoutInit;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class InitializerTest extends KernelTestCase
{
    protected static $class = InitializerTestKernel::class;


    public function testInitializer()
    {
        $this->assertEquals('FIRST', EnumWithoutInit::$FIRST->name());

    }

    protected function setUp(): void
    {
         self::bootKernel();
    }

}

class InitializerTestKernel extends BaseKernel
{
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        parent::registerContainerConfiguration($loader);
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('pa_enum', [
                'initializer' => [
                    'path' => [
                        __DIR__ . '/Fixtures'
                    ],
                ],
            ]);
        });
    }

}