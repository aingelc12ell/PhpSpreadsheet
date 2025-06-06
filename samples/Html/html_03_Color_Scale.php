<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Html as HtmlWriter;

require __DIR__ . '/../Header.php';

$inputFileName = 'ColourScale.xlsx';
$inputFilePath = __DIR__ . '/../templates/' . $inputFileName;

$codePath = $helper->isCli() ? ('samples/templates/' . $inputFileName) : ('<code>' . 'samples/templates/' . $inputFileName . '</code>');
$helper->log('Read ' . $codePath . ' with color scale');
$reader = IOFactory::createReader('Xlsx');
$reader->setReadDataOnly(false);
$spreadsheet = $reader->load($inputFilePath);
$helper->log('Enable conditional formatting output');

function writerCallback(HtmlWriter $writer): void
{
    $writer->setPreCalculateFormulas(true);
    $writer->setConditionalFormatting(true);
}

// Save
$helper->write($spreadsheet, __FILE__, ['Html'], false, writerCallback: writerCallback(...));
