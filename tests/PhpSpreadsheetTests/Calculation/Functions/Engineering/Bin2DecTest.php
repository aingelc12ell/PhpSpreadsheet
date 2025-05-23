<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Engineering;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Calculation\Engineering\ConvertBinary;
use PhpOffice\PhpSpreadsheet\Calculation\Exception as CalculationException;
use PhpOffice\PhpSpreadsheet\Calculation\Functions;
use PhpOffice\PhpSpreadsheet\Calculation\Information\ExcelError;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheetTests\Calculation\Functions\FormulaArguments;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class Bin2DecTest extends TestCase
{
    private string $compatibilityMode;

    protected function setUp(): void
    {
        $this->compatibilityMode = Functions::getCompatibilityMode();
    }

    protected function tearDown(): void
    {
        Functions::setCompatibilityMode($this->compatibilityMode);
    }

    #[DataProvider('providerBIN2DEC')]
    public function testDirectCallToBIN2DEC(float|int|string $expectedResult, bool|int|string $arg1): void
    {
        $result = ConvertBinary::toDecimal($arg1);
        self::assertSame($expectedResult, $result);
    }

    #[DataProvider('providerBIN2DEC')]
    public function testBIN2DECAsFormula(mixed $expectedResult, mixed ...$args): void
    {
        $arguments = new FormulaArguments(...$args);

        $calculation = Calculation::getInstance();
        $formula = "=BIN2DEC({$arguments})";

        /** @var float|int|string */
        $result = $calculation->calculateFormula($formula);
        self::assertSame($expectedResult, $result);
    }

    #[DataProvider('providerBIN2DEC')]
    public function testBIN2DECInWorksheet(mixed $expectedResult, mixed ...$args): void
    {
        $arguments = new FormulaArguments(...$args);

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $argumentCells = $arguments->populateWorksheet($worksheet);
        $formula = "=BIN2DEC({$argumentCells})";

        $result = $worksheet->setCellValue('A1', $formula)
            ->getCell('A1')
            ->getCalculatedValue();
        self::assertSame($expectedResult, $result);

        $spreadsheet->disconnectWorksheets();
    }

    public static function providerBIN2DEC(): array
    {
        return require 'tests/data/Calculation/Engineering/BIN2DEC.php';
    }

    #[DataProvider('providerUnhappyBIN2DEC')]
    public function testBIN2DECUnhappyPath(string $expectedException, mixed ...$args): void
    {
        $arguments = new FormulaArguments(...$args);

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $argumentCells = $arguments->populateWorksheet($worksheet);
        $formula = "=BIN2DEC({$argumentCells})";

        $this->expectException(CalculationException::class);
        $this->expectExceptionMessage($expectedException);
        $worksheet->setCellValue('A1', $formula)
            ->getCell('A1')
            ->getCalculatedValue();

        $spreadsheet->disconnectWorksheets();
    }

    public static function providerUnhappyBIN2DEC(): array
    {
        return [
            ['Formula Error: Wrong number of arguments for BIN2DEC() function'],
        ];
    }

    #[DataProvider('providerBIN2DECOds')]
    public function testBIN2DECOds(float|int|string $expectedResult, bool $arg1): void
    {
        Functions::setCompatibilityMode(
            Functions::COMPATIBILITY_OPENOFFICE
        );

        $result = ConvertBinary::toDecimal($arg1);
        self::assertSame($expectedResult, $result);
    }

    public static function providerBIN2DECOds(): array
    {
        return require 'tests/data/Calculation/Engineering/BIN2DECOpenOffice.php';
    }

    public function testBIN2DECFractional(): void
    {
        $calculation = Calculation::getInstance();
        $formula = '=BIN2DEC(101.1)';

        Functions::setCompatibilityMode(
            Functions::COMPATIBILITY_GNUMERIC
        );
        /** @var float|int|string */
        $result = $calculation->calculateFormula($formula);
        self::assertSame(5, $result, 'Gnumeric');

        Functions::setCompatibilityMode(
            Functions::COMPATIBILITY_OPENOFFICE
        );
        /** @var float|int|string */
        $result = $calculation->calculateFormula($formula);
        self::assertSame(ExcelError::NAN(), $result, 'OpenOffice');

        Functions::setCompatibilityMode(
            Functions::COMPATIBILITY_EXCEL
        );
        /** @var float|int|string */
        $result = $calculation->calculateFormula($formula);
        self::assertSame(ExcelError::NAN(), $result, 'Excel');
    }

    #[DataProvider('providerBin2DecArray')]
    public function testBin2DecArray(array $expectedResult, string $value): void
    {
        $calculation = Calculation::getInstance();

        $formula = "=BIN2DEC({$value})";
        $result = $calculation->calculateFormula($formula);
        self::assertEquals($expectedResult, $result);
    }

    public static function providerBin2DecArray(): array
    {
        return [
            'row/column vector' => [
                [[4, 7, 63, 153, 204, 341]],
                '{"100", "111", "111111", "10011001", "11001100", "101010101"}',
            ],
        ];
    }
}
