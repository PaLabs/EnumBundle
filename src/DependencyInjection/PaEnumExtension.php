<?php


namespace PaLabs\EnumBundle\DependencyInjection;

use PaLabs\EnumBundle\Form\EnumType;
use PaLabs\EnumBundle\Translator\EnumTranslator;
use PaLabs\EnumBundle\Twig\EnumExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class PaEnumExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->configureTranslationDomain($container, $config);
    }

    private function configureTranslationDomain(ContainerBuilder $container, array $config): void
    {
        if (!isset($config['translator'])) {
            $container->removeDefinition(EnumTranslator::class);
            $container->removeDefinition(EnumType::class);
            $container->removeDefinition(EnumExtension::class);
            return;
        }
        $translatorDefinition = $container->getDefinition(EnumTranslator::class);
        $translatorDefinition->setArgument(1, $config['translator']['domain']);
    }


}