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
    private EnumTranslator $translator;

    public function __construct(EnumTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $choiceBuilder = function (Options $options) {
            /** @var Enum $enum */
            $enum = $options['type'];
            $choices = [];
            if(isset($options['items'])) {
                $values = $options['items'];
            } else {
                $values = $enum::values();
            }
            foreach ($values as $value) {
                $translatedLabel = $this->translator->translate($value, $options['value_translation_domain']);
                $choices[$translatedLabel] = $value;
            }
            return $choices;
        };

        $choiceResolver = function ($value = null) {
            if ($value === null) {
                return null;
            }
            if ($value instanceof Enum) {
                return $value->name();
            }
            return $value;
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

    public function getParent()
    {
        return ChoiceType::class;
    }
}
