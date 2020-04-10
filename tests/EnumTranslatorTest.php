<?php


namespace PaLabs\EnumBundle\Test;


use PaLabs\EnumBundle\PaEnumBundle;
use PaLabs\EnumBundle\Test\Fixtures\ActionEnum;
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

    private function getTranslator(): EnumTranslator
    {
        /** @var EnumTranslator $translator */
        $translator = self::$kernel->getContainer()->get(EnumTranslator::class);
        return $translator;
    }

    public function testNotExistingTranslation()
    {
        $this->assertEquals('ActionEnum.NOT_TRANSLATED_ACTION', $this->getTranslator()->translate(ActionEnum::$NOT_TRANSLATED_ACTION));
    }

    public function testTranslateNull()
    {
        $this->assertEmpty($this->getTranslator()->translate(null));
    }

    public function testEnumPrefix()
    {
        $this->assertEquals('action_list_view', $this->getTranslator()->translate(ActionEnum::$VIEW, 'enums', 'action_list'));
    }

    protected function setUp(): void
    {
        self::bootKernel();
    }
}

class TranslatorKernel extends Kernel
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
                    'default_path' => __DIR__ . '/Fixtures/translations'
                ]

            ]);
            $container->loadFromExtension('pa_enum', [
                'translator' => [
                    'domain' => 'enums',
                ],
            ]);
        });
    }

    public function getCacheDir()
    {
        return sprintf('%s/../var/%s/cache/%s', __DIR__, (new \ReflectionClass($this))->getShortName(), $this->environment);
    }

}