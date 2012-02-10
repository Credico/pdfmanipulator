<?php
namespace PdfManipulator;

use Imagick;

class PdfToImageConvertor
{
	private $width;
	private $height;
	
	public function __construct($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
	}
	
	/** @return Array One image per pdf page */
	public function convert($original)
	{
		$sourceFile = tempnam(sys_get_temp_dir(), 'pdf');
		file_put_contents($sourceFile, $original);
		
		$im = new Imagick($sourceFile);
		$count = $im->getNumberImages();
		$results = array();

		for($i = 0; $i < $count; $i++)
		{
			$sourcePdf = new Imagick();
			$sourcePdf->readImage($sourceFile."[$i]");

			// put on top of white background
			$image = new Imagick();
			$image->newImage($this->width, $this->height, "white");
			$image->compositeimage($sourcePdf, Imagick::COMPOSITE_OVER, 0, 0);
			$image->setImageFormat('jpg');
			// $image->setResolution(144, 144);

			$image->setImageUnits(Imagick::RESOLUTION_PIXELSPERINCH);
			$image->setImageCompression(Imagick::COMPRESSION_JPEG);
			$image->setImageCompressionQuality(75);

			$results[$i] = $image->getImageBlob(); 
			
			$image->clear();
			$image->destroy();

		}
		
		@unlink($sourceFile);
		
		return $results;
	}
}

