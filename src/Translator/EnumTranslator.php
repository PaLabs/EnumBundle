<?php

namespace PaLabs\EnumBundle\Translator;

use PaLabs\Enum\Enum;
use ReflectionClass;
use Symfony\Contracts\Translation\TranslatorInterface;

class EnumTranslator
{
    private TranslatorInterface $translator;
    private string $defaultTranslationDomain;

    public function __construct(
        TranslatorInterface $translator, string $defaultTranslationDomain)
    {
        $this->translator = $translator;
        $this->defaultTranslationDomain = $defaultTranslationDomain;
    }

    public function translate(?Enum $enum = null, string $translationDomain = null, string $enumPrefix = null): string
    {
        if($enum === null) {
            return '';
        }
        $translationDomain = $translationDomain ?: $this->defaultTranslationDomain;
        $enumPrefix = $enumPrefix ?: $this->enumName($enum);
        return $this->translator->trans($this->translationKey($enum, $enumPrefix), [], $translationDomain);
    }

    private function translationKey(Enum $enum, string $enumPrefix): string
    {
        return sprintf('%s.%s', $enumPrefix, $enum->name());
    }

    private function enumName(Enum $enum): string
    {
        return (new ReflectionClass($enum))->getShortName();
    }


}
