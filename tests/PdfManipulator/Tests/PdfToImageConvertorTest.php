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
		$results = $convertor->convert(file_get_contents(__DIR__.'/fixture/original1.pdf'));
	
		foreach($results as $result) {
			file_put_contents(__DIR__.'/fixture/converted.jpg', $result);
		}
		
		$this->assertCount(1, $results);
		$this->assertFileEquals(__DIR__.'/fixture/expected_original1.jpg', __DIR__.'/fixture/converted.jpg', 'The converted jpg differs from the expected jpg');
		unlink(__DIR__.'/fixture/converted.jpg');
	}
	

}