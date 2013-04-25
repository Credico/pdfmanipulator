<?php

namespace Tests\PdfManipulator;

class PdfToTextConverterTest extends \PHPUnit_Framework_TestCase
{
	/** @test */
	public function testPdfToTextConversion()
	{
		$pdf2txt = new \PdfManipulator\PdfToTextConverter();
		$this->assertContains('Mogen wij u vriendelijk verzoeken', $pdf2txt->convert(__DIR__ . '/fixture/reminder_color.pdf'));
	}
}

