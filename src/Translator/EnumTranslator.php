<?php

namespace PaLabs\EnumBundle\Translator;

use PaLabs\Enum\Enum;
use ReflectionClass;
use Symfony\Contracts\Translation\TranslatorInterface;

class EnumTranslator
{

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly string $defaultTranslationDomain)
    {
    }

    public function translate(Enum|\UnitEnum|null $enum = null, string $translationDomain = null, string $enumPrefix = null): string
    {
        if($enum === null) {
            return '';
        }
        $translationDomain = $translationDomain ?: $this->defaultTranslationDomain;
        $enumPrefix = $enumPrefix ?: $this->enumName($enum);
        return $this->translator->trans($this->translationKey($enum, $enumPrefix), [], $translationDomain);
    }

    private function translationKey(Enum|\UnitEnum $enum, string $enumPrefix): string
    {
        $enumName = match(true){
            $enum instanceof Enum => $enum->name(),
            $enum instanceof \UnitEnum => $enum->name
        };
        return sprintf('%s.%s', $enumPrefix, $enumName);
    }

    private function enumName(Enum|\UnitEnum $enum): string
    {
        return (new ReflectionClass($enum))->getShortName();
    }


}
