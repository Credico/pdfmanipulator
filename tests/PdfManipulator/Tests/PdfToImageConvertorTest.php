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
			array(__DIR__.'/fixture/fpdf_bw.pdf', __DIR__.'/fixture/result_fpdf_bw.jpg'),
			array(__DIR__.'/fixture/fpdf_color.pdf', __DIR__.'/fixture/result_fpdf_color.jpg'),
			array(__DIR__.'/fixture/mac_bw.pdf', __DIR__.'/fixture/result_mac_bw.jpg'),
			array(__DIR__.'/fixture/mac_color.pdf', __DIR__.'/fixture/result_mac_color.jpg'), 
		);
	}
	
	/**
	 * @test
	 * @dataProvider providePdfs
	 * @medium
	 * @depends ImagickIsInstalled
	 */
	public function ConvertsToJpg($original, $location)
	{
		$convertor = new PdfToImageConvertor(640, 905);
		$results = $convertor->convert(file_get_contents($original));
	
		foreach($results as $result) {
			file_put_contents($location, $result);
		}
		
		$this->assertCount(1, $results);
	}
	

}