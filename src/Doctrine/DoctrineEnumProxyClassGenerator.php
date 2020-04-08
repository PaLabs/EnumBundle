<?php

namespace PaLabs\EnumBundle\Doctrine;


use ReflectionClass;

class DoctrineEnumProxyClassGenerator
{
    public const DB_ENUM_NAMESPACE = 'Sciact\\Enum\\Doctrine';

    public function proxyClassName(string $enumClass): string
    {
        $enumClassName = (new ReflectionClass($enumClass))->getShortName();
        return $enumClassName . 'DoctrineEnum';
    }

    public function proxyClassFullName(string $enumClass): string {
        return self::DB_ENUM_NAMESPACE . '\\' . $this->proxyClassName($enumClass);
    }

    public function generateProxyClass(string $enumClass): string
    {
        $proxyClassTemplate = <<<EOF
<?php

namespace <namespace>;        

class <proxyClassName> extends \<proxyClassBase>  {
    protected static string \$enumClass = '\<enumClass>';
}
EOF;
        $placeholders = [
            'namespace'      => self::DB_ENUM_NAMESPACE,
            'proxyClassName' => self::proxyClassName($enumClass),
            'proxyClassBase' => DoctrineEnumAbstractType::class,
            'enumClass'      => $enumClass,
        ];

        return $this->generateCode($proxyClassTemplate, $placeholders);
    }

    private function generateCode(string $classTemplate, array $placeholders): string
    {
        $placeholderNames = array_map(function ($placeholderName) {
            return '<' . $placeholderName . '>';
        }, array_keys($placeholders));
        $placeHolderValues = array_values($placeholders);

        return str_replace($placeholderNames, $placeHolderValues, $classTemplate);
    }
}