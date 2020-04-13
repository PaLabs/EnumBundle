<?php


namespace PaLabs\EnumBundle\Test\Fixtures;


use PaLabs\Enum\Enum;

class EnumWithoutInit extends Enum
{
    public static EnumWithoutInit $FIRST, $SECOND;
}