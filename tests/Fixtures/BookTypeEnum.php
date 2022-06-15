<?php


namespace PaLabs\EnumBundle\Test\Fixtures;


enum BookTypeEnum: string
{

    case MONOGRAPHY = 'bk_monography';
    case THESES = 'bk_theses';
    case COMPILATION = 'bk_compilation';
}
