<?php

namespace PaLabs\EnumBundle\Test;

use PaLabs\EnumBundle\Doctrine\MysqlEnumColumnDefinition;
use PaLabs\EnumBundle\Test\Fixtures\BookTypeEnum;
use PaLabs\EnumBundle\Test\Fixtures\SomeBackedEnum;
use PHPUnit\Framework\TestCase;
use PaLabs\EnumBundle\Test\Fixtures\SomeUnitEnum;

class MysqlEnumColumnDefinitionTest extends TestCase
{

    public function testUnitEnum() {
        $this->assertEquals("ENUM('FIRST_VALUE','SECOND_VALUE') NOT NULL", (new MysqlEnumColumnDefinition(SomeUnitEnum::class))->__toString());
    }

    public function testBackedEnum() {
        $this->assertEquals("ENUM('one','two') NOT NULL", (new MysqlEnumColumnDefinition(SomeBackedEnum::class))->__toString());
    }

    public function testDoctrineEnum() {
        $this->assertEquals("ENUM('bk_monography','bk_theses','bk_compilation') NOT NULL", (new MysqlEnumColumnDefinition(BookTypeEnum::class))->__toString());

    }
}