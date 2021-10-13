<?php

namespace Tests\Unit;

use Dnetix\Asoban\AsobanParser;
use Dnetix\Asoban\Exceptions\AsobanException;
use Tests\BaseTestCase;

class Format2001Test extends BaseTestCase
{
    public function testItParsesABasic2001File()
    {
        $file = $this->generateFile();

        $asoban = AsobanParser::load($file, AsobanParser::F_2001)->parse();

        $this->assertEquals('0000012345', $asoban->header()->nit());
        $this->assertEquals('00849514521', $asoban->header()->accountNumber());
        $this->assertEquals('01', $asoban->header()->accountType());
    }

    public function testItThrowsExceptionWhenFileDoesntExists()
    {
        $this->expectException(AsobanException::class);
        AsobanParser::load(__DIR__ . '/files/not-exists', AsobanParser::F_2001)->parse();
    }
}
