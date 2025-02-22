<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Statistical;

class VarATest extends AllSetupTeardown
{
    #[\PHPUnit\Framework\Attributes\DataProvider('providerVARA')]
    public function testVARA(mixed $expectedResult, mixed ...$args): void
    {
        $this->runTestCases('VARA', $expectedResult, ...$args);
    }

    public static function providerVARA(): array
    {
        return require 'tests/data/Calculation/Statistical/VARA.php';
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerOdsVARA')]
    public function testOdsVARA(mixed $expectedResult, mixed ...$args): void
    {
        $this->setOpenOffice();
        $this->runTestCases('VARA', $expectedResult, ...$args);
    }

    public static function providerOdsVARA(): array
    {
        return require 'tests/data/Calculation/Statistical/VARA_ODS.php';
    }
}
