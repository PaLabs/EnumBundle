<?php

namespace PaLabs\EnumBundle\Twig;


use PaLabs\Enum\Enum;
use PaLabs\EnumBundle\Translator\EnumTranslator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

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

    public function translate(Enum|\UnitEnum|null $enum = null, string $translationDomain = null, string $enumPrefix = null): string
    {
        return $this->translator->translate($enum, $translationDomain, $enumPrefix);
    }
}
