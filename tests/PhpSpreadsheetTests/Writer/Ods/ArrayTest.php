<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Writer\Ods;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Calculation\Functions;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;
use PhpOffice\PhpSpreadsheetTests\Functional\AbstractFunctional;

class ArrayTest extends AbstractFunctional
{
    private string $arrayReturnType;

    private string $samplesPath = 'tests/data/Writer/Ods';

    private string $compatibilityMode;

    private bool $skipInline = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->compatibilityMode = Functions::getCompatibilityMode();
        Functions::setCompatibilityMode(Functions::COMPATIBILITY_OPENOFFICE);
        $this->arrayReturnType = Calculation::getArrayReturnType();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Functions::setCompatibilityMode($this->compatibilityMode);
        Calculation::setArrayReturnType($this->arrayReturnType);
    }

    public function testArrayXml(): void
    {
        Calculation::setArrayReturnType(Calculation::RETURN_ARRAY_AS_ARRAY);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getCell('A1')->setValue(1);
        $sheet->getCell('A2')->setValue(1);
        $sheet->getCell('A3')->setValue(3);
        $sheet->getCell('B1')->setValue('=UNIQUE(A1:A3)');

        $content = new Content(new Ods($spreadsheet));
        $xml = $content->write();
        self::assertXmlStringEqualsXmlFile($this->samplesPath . '/content-arrays.xml', $xml);
    }

    public function testArray(): void
    {
        Calculation::setArrayReturnType(Calculation::RETURN_ARRAY_AS_ARRAY);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getCell('A1')->setValue(1);
        $sheet->getCell('A2')->setValue(1);
        $sheet->getCell('A3')->setValue(3);
        $sheet->getCell('B1')->setValue('=UNIQUE(A1:A3)');
        $reloadedSpreadsheet = $this->writeAndReload($spreadsheet, 'Ods');
        $sheet = $reloadedSpreadsheet->getActiveSheet();
        self::assertEquals('1', $sheet->getCell('A1')->getValue());
        self::assertEquals('1', $sheet->getCell('A2')->getValue());
        self::assertEquals('3', $sheet->getCell('A3')->getValue());
        self::assertEquals('3', $sheet->getCell('B2')->getValue());
        self::assertNull($sheet->getCell('B3')->getValue());
        self::assertEquals('=UNIQUE(A1:A3)', $sheet->getCell('B1')->getValue());
        $spreadsheet->disconnectWorksheets();
        $reloadedSpreadsheet->disconnectWorksheets();
    }

    public function testInlineArrays(): void
    {
        if ($this->skipInline) {
            self::markTestIncomplete('Ods Reader/Writer alter commas and semi-colons within formulas, interfering with inline arrays');
        }
        Calculation::setArrayReturnType(Calculation::RETURN_ARRAY_AS_ARRAY);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getCell('A1')->setValue('=UNIQUE({1;1;2;1;3;2;4;4;4})');
        $sheet->getCell('D1')->setValue('=UNIQUE({1,1,2,1,3,2,4,4,4},true)');
        $reloadedSpreadsheet = $this->writeAndReload($spreadsheet, 'Ods');
        $spreadsheet->disconnectWorksheets();
        $rsheet = $reloadedSpreadsheet->getActiveSheet();
        $expected = [
            ['=UNIQUE({1,1,2,1,3,2,4,4,4})', null, null, '=UNIQUE({1,1,2,1,3,2,4,4,4},true)', 2, 3, 4],
            [2, null, null, null, null, null, null],
            [3, null, null, null, null, null, null],
            [4, null, null, null, null, null, null],
        ];
        self::assertSame($expected, $rsheet->toArray(null, false, false));
        self::assertSame('1', $rsheet->getCell('A1')->getCalculatedValueString());
        self::assertSame('1', $rsheet->getCell('D1')->getCalculatedValueString());
        $reloadedSpreadsheet->disconnectWorksheets();
    }
}
