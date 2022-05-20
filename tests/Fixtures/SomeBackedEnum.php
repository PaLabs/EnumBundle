<?php

namespace PaLabs\EnumBundle\Test\Fixtures;

enum SomeBackedEnum: string
{
    case FIRST_VALUE = 'one';
    case SECOND_VALUE = 'two';
}