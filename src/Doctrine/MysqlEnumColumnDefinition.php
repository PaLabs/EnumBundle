<?php

namespace PaLabs\EnumBundle\Doctrine;

use PaLabs\Enum\Enum;

/**
 * Using in doctrine #[Column] attribute to define the mysql enum type.
 * Example:  #[ORM\Column(name: 'some_column', enumType: MyEnum::class,
 *              columnDefinition: new MysqlEnumColumnDefinition(MyEnum::class))]
 * note: this is a platform lock method - if you want to swith to another platform, that you need to use another column definition
 */
class MysqlEnumColumnDefinition implements \Stringable
{

    public function __construct(
        private readonly string $enumClass,
        private readonly bool $nullable = false)
    {
    }

    public function __toString(): string
    {
        $cases = $this->cases();

        $enumValues = implode(',', array_map(fn($enum) => sprintf("'%s'", $this->enumName($enum)), $cases));
        $baseType =  sprintf('ENUM(%s)', $enumValues);

        $baseType .= ' ' . ($this->nullable ? 'NULL' : 'NOT NULL');
        return $baseType;
    }

    private function cases(): array
    {
        $enum = $this->enumClass;
        return match(true) {
            is_subclass_of($enum,Enum::class) => $enum::values(),
            is_subclass_of($enum, \UnitEnum::class) => $enum::cases(),
            default => throw new \LogicException(sprintf('Unknown enum type %s', $enum))
        };
    }

    private function enumName(mixed $enum): string
    {
        return match(true) {
            $enum instanceof DoctrineEnum => $enum->sqlValue(),
            $enum instanceof Enum => $enum->name(),
            $enum instanceof \BackedEnum => $enum->value,
            $enum instanceof \UnitEnum => $enum->name,
            default => throw new \LogicException(sprintf('Unknown enum type %s', get_class($enum)))
        };
    }
}