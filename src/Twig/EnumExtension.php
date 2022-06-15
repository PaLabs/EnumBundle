<?php

namespace PaLabs\EnumBundle\Twig;


use PaLabs\EnumBundle\Translator\EnumTranslator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use UnitEnum;

class EnumExtension extends AbstractExtension
{

    public function __construct(private readonly EnumTranslator $translator)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('translate_enum', [$this, 'translate']),
        ];
    }

    public function translate(?UnitEnum $enum = null, string $translationDomain = null, string $enumPrefix = null): string
    {
        return $this->translator->translate($enum, $translationDomain, $enumPrefix);
    }
}
