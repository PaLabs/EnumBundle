<?php

namespace PaLabs\EnumBundle\Translator;

use ReflectionClass;
use Symfony\Contracts\Translation\TranslatorInterface;
use UnitEnum;

class EnumTranslator
{

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly string $defaultTranslationDomain)
    {
    }

    public function translate(?UnitEnum $enum = null, string $translationDomain = null, string $enumPrefix = null): string
    {
        if($enum === null) {
            return '';
        }
        $translationDomain = $translationDomain ?: $this->defaultTranslationDomain;
        $enumPrefix = $enumPrefix ?: $this->enumName($enum);
        return $this->translator->trans($this->translationKey($enum, $enumPrefix), [], $translationDomain);
    }

    private function translationKey(UnitEnum $enum, string $enumPrefix): string
    {
        return sprintf('%s.%s', $enumPrefix, $enum->name);
    }

    private function enumName(UnitEnum $enum): string
    {
        return (new ReflectionClass($enum))->getShortName();
    }


}
