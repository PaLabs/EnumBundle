<?php


namespace PaLabs\EnumBundle\DependencyInjection;

use PaLabs\EnumBundle\Doctrine\DoctrineEnumClassCacheWarmer;
use PaLabs\EnumBundle\Doctrine\DoctrineEnumProxyClassGenerator;
use PaLabs\EnumBundle\Doctrine\EnumPathScanner;
use PaLabs\EnumBundle\Form\EnumType;
use PaLabs\EnumBundle\Translator\EnumTranslator;
use PaLabs\EnumBundle\Twig\EnumExtension;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;


class PaEnumExtension extends Extension
{
    private const DOCTRINE_TYPES_PARAMETER_NAME = 'doctrine.dbal.connection_factory.types';

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->configureTranslationDomain($container, $config);
        $this->configureDoctrine($container, $config);
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

    private function configureDoctrine(ContainerBuilder $container, array $config): void
    {
        if (!isset($config['doctrine']) || !isset($config['doctrine']['path']) || empty($config['doctrine']['path'])) {
            $container->removeDefinition(DoctrineEnumClassCacheWarmer::class);
            return;
        }

        $paths = $config['doctrine']['path'];
        $container->getDefinition(DoctrineEnumClassCacheWarmer::class)->setArgument(0, $paths);

        $enumClasses = (new EnumPathScanner())->enumClassesInPaths($paths);
        if (empty($enumClasses)) {
            return;
        }

        $proxyClassGenerator = new DoctrineEnumProxyClassGenerator();
        $types = [];
        foreach ($enumClasses as $classStr) {
            $class = (new ReflectionClass($classStr));

            $types[$class->getShortName()] = ['class' => $proxyClassGenerator->proxyClassFullName($classStr)];
            $types[$class->getName()] = ['class' => $proxyClassGenerator->proxyClassFullName($classStr)];
        }

        $existingTypes = $container->has(self::DOCTRINE_TYPES_PARAMETER_NAME) ? $container->getParameter(self::DOCTRINE_TYPES_PARAMETER_NAME) : [];
        $allTypes = array_merge($existingTypes, $types);
        $container->setParameter(self::DOCTRINE_TYPES_PARAMETER_NAME, $allTypes);

        // Need generate proxy classes for doctrine loader
        (new DoctrineEnumClassCacheWarmer($paths))->warmUp($container->getParameter('kernel.cache_dir'));

    }


}