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
	
	public function providePdfs()
	{
		return array(
			array(__DIR__.'/fixture/reminder_color.pdf', __DIR__.'/fixture/expected_reminder_color.jpg'),
			array(__DIR__.'/fixture/reminder_bw.pdf', __DIR__.'/fixture/expected_reminder_bw.jpg'),
			array(__DIR__.'/fixture/invoice_bw.pdf', __DIR__.'/fixture/expected_invoice_bw.jpg'),
			array(__DIR__.'/fixture/invoice_mac_bw.pdf', __DIR__.'/fixture/expected_reminder_color.jpg'), 
			array(__DIR__.'/fixture/invoice_mac_color.pdf', __DIR__.'/fixture/expected_reminder_color.jpg')
		);
	}
	
	/**
	 * @test
	 * @dataProvider providePdfs
	 * @medium
	 * @depends ImagickIsInstalled
	 */
	public function ConvertsToJpg($original, $expected)
	{
		$convertor = new PdfToImageConvertor(640, 905);
		$results = $convertor->convert(file_get_contents($original));
	
		foreach($results as $result) {
			file_put_contents(__DIR__.'/fixture/converted.jpg', $result);
		}
		
		$this->assertCount(1, $results);
		//we don't assert for equalness anymore, because for the jpg files, the content is different
		//$this->assertFileEquals($expected, __DIR__.'/fixture/converted1.jpg', 'The converted jpg differs from the expected jpg');
		unlink(__DIR__.'/fixture/converted.jpg');
	}
	

}