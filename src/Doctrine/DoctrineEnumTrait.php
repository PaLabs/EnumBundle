<?php


namespace PaLabs\EnumBundle\Doctrine;


trait DoctrineEnumTrait
{

    private string $sqlValue;

    public function __construct(string $sqlValue)
    {
        $this->sqlValue = $sqlValue;
    }

    public function sqlValue(): string {
        return $this->sqlValue;
    }

    public function __toString(): string
    {
        return $this->sqlValue;
    }

}