<?php

namespace PaLabs\EnumBundle\Doctrine;


use Exception;
use LogicException;
use ReflectionClass;
use PaLabs\Enum\Enum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Types\Type;

class DoctrineEnumAbstractType extends Type
{
    protected static string $enumClass = '';

    private static array $enumSqlValues = [];

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        /** @var Enum $enum */
        $enum = static::$enumClass;
        $values = implode(", ", array_map(
            fn(Enum $enum) => sprintf("'%s'", $this->sqlValue($enum)),
            $enum::values()
        ));

        if ($platform instanceof MySqlPlatform) {
            return sprintf('ENUM(%s)', $values);
        } elseif ($platform instanceof SqlitePlatform) {
            return sprintf('TEXT CHECK(%s IN (%s))', $fieldDeclaration['name'], $values);
        } elseif ($platform instanceof PostgreSqlPlatform) {
            return sprintf('VARCHAR(255) CHECK(%s IN (%s))', $fieldDeclaration['name'], $values);
        } else {
            throw new Exception(sprintf("Sorry, platform %s currently not supported enums", $platform->getName()));
        }

    }

    private function sqlValue(Enum $enum): string
    {
        return $enum instanceof DoctrineEnum ? $enum->sqlValue() : strtolower($enum->name());
    }

    public function getName()
    {
        $enum = static::$enumClass;
        return (new ReflectionClass($enum))->getShortName();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        /** @var Enum $enum */
        $enum = static::$enumClass;
        if (!isset(self::$enumSqlValues[static::$enumClass])) {
            foreach ($enum::values() as $enumObj) {
                $sqlValue = $this->sqlValue($enumObj);
                self::$enumSqlValues[static::$enumClass][$sqlValue] = $enumObj;
            }
        }
        if (!isset(self::$enumSqlValues[static::$enumClass][$value])) {
            throw new LogicException(sprintf('Unknown value: %s, enum %s',
                $value, static::$enumClass));
        }
        return self::$enumSqlValues[static::$enumClass][$value];
    }

    public function convertToDatabaseValue($enum, AbstractPlatform $platform)
    {
        if ($enum === null) {
            return null;
        }
        if (!$enum instanceof Enum) {
            throw new LogicException(sprintf('Enum value must be instance of Enum, %s', get_class($enum)));
        }
        return $this->sqlValue($enum);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

}