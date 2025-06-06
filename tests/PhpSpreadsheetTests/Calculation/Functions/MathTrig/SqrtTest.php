<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\MathTrig;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;

class SqrtTest extends AllSetupTeardown
{
    #[\PHPUnit\Framework\Attributes\DataProvider('providerSQRT')]
    public function testSQRT(mixed $expectedResult, mixed $number = 'omitted'): void
    {
        $sheet = $this->getSheet();
        $this->mightHaveException($expectedResult);
        $this->setCell('A1', $number);
        if ($number === 'omitted') {
            $sheet->getCell('B1')->setValue('=SQRT()');
        } else {
            $sheet->getCell('B1')->setValue('=SQRT(A1)');
        }
        $result = $sheet->getCell('B1')->getCalculatedValue();
        self::assertEqualsWithDelta($expectedResult, $result, 1E-6);
    }

    public static function providerSqrt(): array
    {
        return require 'tests/data/Calculation/MathTrig/SQRT.php';
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerSqrtArray')]
    public function testSqrtArray(array $expectedResult, string $array): void
    {
        $calculation = Calculation::getInstance();

        $formula = "=SQRT({$array})";
        $result = $calculation->_calculateFormulaValue($formula);
        self::assertEqualsWithDelta($expectedResult, $result, 1.0e-14);
    }

    public static function providerSqrtArray(): array
    {
        return [
            'row vector' => [[[3, 3.5355339059327378, 4.898979485566356]], '{9, 12.5, 24}'],
            'column vector' => [[[3], [3.5355339059327378], [4.898979485566356]], '{9; 12.5; 24}'],
            'matrix' => [[[3, 3.5355339059327378], [4.898979485566356, 8]], '{9, 12.5; 24, 64}'],
        ];
    }
}
