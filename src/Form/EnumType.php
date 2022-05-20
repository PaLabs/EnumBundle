<?php

namespace PaLabs\EnumBundle\Form;

use PaLabs\Enum\Enum;
use PaLabs\EnumBundle\Translator\EnumTranslator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnumType extends AbstractType
{

    public function __construct(private readonly EnumTranslator $translator)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $choiceBuilder = function (Options $options) {
            /** @var Enum|\UnitEnum $enum */
            $enum = $options['type'];
            $choices = [];
            if (isset($options['items'])) {
                $values = $options['items'];
            } else {
                $values = match (true) {
                    $enum instanceof Enum => $enum::values(),
                    $enum instanceof \UnitEnum => $enum::cases(),
                    default => throw new \LogicException(sprintf('Unknown enum type %s', get_class($enum)))
                };
            }
            foreach ($values as $value) {
                $translatedLabel = $this->translator->translate($value, $options['value_translation_domain']);
                $choices[$translatedLabel] = $value;
            }
            return $choices;
        };

        $choiceResolver = function ($value = null) {
            return match(true) {
                $value === null => null,
                $value instanceof Enum => $value->name(),
                $value instanceof \BackedEnum => $value->name,
                default => $value
            };
        };


        $resolver
            ->setDefaults([
                'value_translation_domain' => 'enums',
                'choices' => $choiceBuilder,
                'choice_value' => $choiceResolver
            ])
            ->setRequired(['type'])
            ->setDefined(['items']);

    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
