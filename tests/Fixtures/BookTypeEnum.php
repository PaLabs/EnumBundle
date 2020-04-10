<?php


namespace PaLabs\EnumBundle\Test\Fixtures;


use PaLabs\Enum\Enum;
use PaLabs\EnumBundle\Doctrine\DoctrineEnum;
use PaLabs\EnumBundle\Doctrine\DoctrineEnumTrait;

class BookTypeEnum extends Enum implements DoctrineEnum
{
    use DoctrineEnumTrait;

    public static BookTypeEnum $MONOGRAPHY, $THESES, $COMPILATION;
}

BookTypeEnum::$MONOGRAPHY = new BookTypeEnum('bk_monography');
BookTypeEnum::$THESES = new BookTypeEnum('bk_theses');
BookTypeEnum::$COMPILATION = new BookTypeEnum('bk_compilation');

BookTypeEnum::init();