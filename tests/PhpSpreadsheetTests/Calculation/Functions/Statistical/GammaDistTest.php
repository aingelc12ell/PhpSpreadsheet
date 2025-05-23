<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Statistical;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;

class GammaDistTest extends AllSetupTeardown
{
    #[\PHPUnit\Framework\Attributes\DataProvider('providerGAMMADIST')]
    public function testGAMMADIST(mixed $expectedResult, mixed ...$args): void
    {
        $this->runTestCases('GAMMA.DIST', $expectedResult, ...$args);
    }

    public static function providerGAMMADIST(): array
    {
        return require 'tests/data/Calculation/Statistical/GAMMADIST.php';
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerGammaDistArray')]
    public function testGammaDistArray(array $expectedResult, string $values, string $alpha, string $beta): void
    {
        $calculation = Calculation::getInstance();

        $formula = "=GAMMA.DIST({$values}, {$alpha}, {$beta}, false)";
        $result = $calculation->_calculateFormulaValue($formula);
        self::assertEqualsWithDelta($expectedResult, $result, 1.0e-14);
    }

    public static function providerGammaDistArray(): array
    {
        return [
            'row/column vectors' => [
                [
                    [0.0012393760883331792, 0.007436256529999079, 0.0669263087699917],
                    [0.012446767091965986, 0.03734030127589798, 0.04200783893538521],
                    [0.018143590657882503, 0.043544617578918025, 0.02508169972545678],
                ],
                '12',
                '{1, 2, 5}',
                '{2; 4; 5}',
            ],
        ];
    }
}
