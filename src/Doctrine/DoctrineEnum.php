<?php


namespace PaLabs\EnumBundle\Doctrine;


interface DoctrineEnum
{
    public function sqlValue(): string;
}