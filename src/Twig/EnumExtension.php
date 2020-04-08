<?php

namespace PaLabs\EnumBundle\Twig;


use PaLabs\Enum\Enum;
use PaLabs\EnumBundle\Translator\EnumTranslator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EnumExtension extends AbstractExtension
{
    private EnumTranslator $translator;

    public function __construct(EnumTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('translate_enum', array($this, 'translate')),
        );
    }

    public function translate(?Enum $enum = null, string $translationDomain = null, string $enumPrefix = null): string
    {
        return $this->translator->translate($enum, $translationDomain, $enumPrefix);
    }
}
