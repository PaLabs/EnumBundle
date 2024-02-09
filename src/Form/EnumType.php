<?php

namespace PaLabs\EnumBundle\Form;

use PaLabs\EnumBundle\Translator\EnumTranslator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnumType extends AbstractType
{
    public const OPTION_TYPE = 'type';
    public const OPTION_ITEMS = 'items';
    public const OPTION_TRANSLATION_DOMAIN = 'value_translation_domain';

    public function __construct(private readonly EnumTranslator $translator)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $choiceBuilder = function (Options $options) {
            $enum = $options[self::OPTION_TYPE];
            $choices = [];

            $values = match (true) {
                isset($options[self::OPTION_ITEMS]) => $options[self::OPTION_ITEMS],
                is_subclass_of($enum, \UnitEnum::class) => $enum::cases(),
                default => throw new \LogicException(sprintf('Unknown enum type %s', $enum))
            };

            foreach ($values as $value) {
                $translatedLabel = $this->translator->translate($value, $options[self::OPTION_TRANSLATION_DOMAIN]);
                $choices[$translatedLabel] = $value;
            }
            return $choices;
        };

        $choiceResolver = function ($value = null) {
            return match (true) {
                $value === null => null,
                $value instanceof \UnitEnum => $value->name,
                default => $value
            };
        };


        $resolver
            ->setDefaults([
                self::OPTION_TRANSLATION_DOMAIN => 'enums',
                'choices' => $choiceBuilder,
                'choice_value' => $choiceResolver
            ])
            ->setRequired([self::OPTION_TYPE])
            ->setDefined([self::OPTION_ITEMS]);

    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public static function options(
        string $type,
        array $items = null,
        string $translationDomain = null,
        bool $required = null): array
    {
        $options = [
            self::OPTION_TYPE => $type
        ];
        if ($items !== null) {
            $options[self::OPTION_ITEMS] = $items;
        }
        if ($translationDomain !== null) {
            $options[self::OPTION_TRANSLATION_DOMAIN] = $translationDomain;
        }
        if($required !== null) {
            $options['required'] = $required;
        }
        return $options;
    }
}
