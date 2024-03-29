<?php

namespace Tests\PdfManipulator;

use PdfManipulator\PdfCombiner;

class PdfCombinerTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @test
	 * @medium
	 */
	public function AddsBackground()
	{
		$pdfCombiner = new PdfCombiner('pdftk');
		$result = $pdfCombiner->addBackground(
			file_get_contents(__DIR__.'/fixture/reminder_color.pdf'),
			file_get_contents(__DIR__.'/fixture/reminder_bw.pdf')
		);

		$this->assertGreaterThan(0, strlen($result), "The resulting pdf should not be zero characters");
		$this->assertEquals(file_get_contents(__DIR__.'/fixture/expected_background.pdf'), $result);
	}

	/**
	 * @test
	 * @medium
	 */
	public function AddsBackgroundToFirstPage()
	{

		$pdfCombiner = new PdfCombiner('pdftk');

		$result = $pdfCombiner->addBackgroundToFirstPage(
			file_get_contents(__DIR__.'/fixture/reminder_multiple.pdf'),
			file_get_contents(__DIR__.'/fixture/mac_color.pdf')
		);
		$expected = file_get_contents(__DIR__.'/fixture/expected_background_first.pdf');
		$this->assertGreaterThan(0, strlen($result), "The resulting pdf should not be zero characters");
		$this->assertStringDistanceInPercent('99', $expected, $result);
	}

	/**
	 * @test
	 * @medium
	 */
	public function Catenates()
	{
		$pdfCombiner = new PdfCombiner('pdftk');
		$result = $pdfCombiner->catenate(array(
			file_get_contents(__DIR__.'/fixture/reminder_color.pdf'),
			file_get_contents(__DIR__.'/fixture/reminder_bw.pdf'),
		));

		$expected = file_get_contents(__DIR__.'/fixture/expected_catenate.pdf');
		$this->assertGreaterThan(0, strlen($result), "The resulting pdf should not be zero characters");
		$this->assertStringDistanceInPercent('99', $expected, $result);
	}

	private function assertStringDistanceInPercent($minimumPercentage, $expected, $actual)
	{
		$percentage = 0;
		similar_text($expected, $actual, $percentage);
		$this->assertGreaterThanOrEqual($minimumPercentage, $percentage, "The distance between the strings should be greater than or equal to $minimumPercentage%, got $percentage%");
	}

}

