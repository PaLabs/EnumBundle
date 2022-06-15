<?php


namespace PaLabs\EnumBundle;


use PaLabs\EnumBundle\DependencyInjection\PaEnumExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PaEnumBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new PaEnumExtension();
    }


}