<?php


namespace PaLabs\EnumBundle\Test\Fixtures;


use PaLabs\Enum\Enum;

class ActionEnum extends Enum
{
    public static ActionEnum $VIEW, $EDIT, $DELETE;
    public static ActionEnum $NOT_TRANSLATED_ACTION;
}
ActionEnum::init();