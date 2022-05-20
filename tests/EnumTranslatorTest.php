<?php


namespace PaLabs\EnumBundle\Test;


use PaLabs\EnumBundle\PaEnumBundle;
use PaLabs\EnumBundle\Test\Fixtures\ActionEnum;
use PaLabs\EnumBundle\Test\Fixtures\BaseKernel;
use PaLabs\EnumBundle\Test\Fixtures\SomeUnitEnum;
use PaLabs\EnumBundle\Translator\EnumTranslator;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class EnumTranslatorTest extends KernelTestCase
{
    protected static $class = TranslatorKernel::class;

    public function testExistingTranslationValue()
    {
        $this->assertEquals('action_view', $this->getTranslator()->translate(ActionEnum::$VIEW));
    }

    public function testNotExistingTranslation()
    {
        $this->assertEquals('ActionEnum.NOT_TRANSLATED_ACTION', $this->getTranslator()->translate(ActionEnum::$NOT_TRANSLATED_ACTION));
    }

    public function testTranslateNull()
    {
        $this->assertEmpty($this->getTranslator()->translate(null));
    }

    public function testTranslateUnitEnum() {
        $this->assertEquals('first value', $this->getTranslator()->translate(SomeUnitEnum::FIRST_VALUE));
    }

    public function testEnumPrefix()
    {
        $this->assertEquals('action_list_view', $this->getTranslator()->translate(ActionEnum::$VIEW, 'enums', 'action_list'));
    }

    private function getTranslator(): EnumTranslator
    {
        /** @var EnumTranslator $translator */
        $translator = self::$kernel->getContainer()->get(EnumTranslator::class);
        return $translator;
    }

    protected function setUp(): void
    {
        self::bootKernel();
    }
}

class TranslatorKernel extends BaseKernel
{
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        parent::registerContainerConfiguration($loader);
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('pa_enum', [
                'translator' => [
                    'domain' => 'enums',
                ],
            ]);
        });
    }
}