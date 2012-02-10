<?php
namespace Tests\PdfManipulator;

use PdfManipulator\PdfToImageConvertor;

class PdfToImageConvertorTest extends \PHPUnit_Framework_TestCase
{

	/** @test */
	public function ImagickIsInstalled()
	{
		$this->assertTrue(class_exists('\Imagick'), 'Imagick is not installed');
	}
	
	/**
	 * @test
	 * @medium
	 * @depends ImagickIsInstalled
	 */
	public function ConvertsToJpg()
	{
		$convertor = new PdfToImageConvertor(640, 905);
		$convertor->convert(__DIR__.'/fixture/original1.pdf');
		
		
		$this->assertTrue(true);
	}
}