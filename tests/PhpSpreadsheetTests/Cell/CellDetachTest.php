<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Cell;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Exception as SpreadsheetException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPUnit\Framework\TestCase;

class CellDetachTest extends TestCase
{
    private ?Spreadsheet $spreadsheet = null;

    protected function tearDown(): void
    {
        if ($this->spreadsheet !== null) {
            $this->spreadsheet->disconnectWorksheets();
            $this->spreadsheet = null;
        }
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerMethodName')]
    public function testDetach(string $method): void
    {
        $this->expectException(SpreadsheetException::class);
        $this->expectExceptionMessage('is not bound to a worksheet');
        $this->spreadsheet = new Spreadsheet();
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->getCell('A1')->detach();
        if (method_exists(Cell::class, $method)) {
            $sheet->getCell('A1')->$method();
        } else {
            self::fail("Cell method $method does not exist");
        }
    }

    public static function providerMethodName(): array
    {
        return [
            ['updateInCollection'],
            ['getColumn'],
            ['getRow'],
            ['hasDataValidation'],
            ['getDataValidation'],
            ['hasHyperlink'],
            ['getHyperlink'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerMethodNameSet')]
    public function testDetachSet(string $method): void
    {
        $this->expectException(SpreadsheetException::class);
        $this->expectExceptionMessage('is not bound to a worksheet');
        $this->spreadsheet = new Spreadsheet();
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->getCell('A1')->detach();
        if (method_exists(Cell::class, $method)) {
            $sheet->getCell('A1')->$method(null);
        } else {
            self::fail("Cell method $method does not exist");
        }
    }

    public static function providerMethodNameSet(): array
    {
        return [
            ['setDataValidation'],
            ['setHyperlink'],
            ['setValue'],
        ];
    }
}
