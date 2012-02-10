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
	
	public function convert($source)
	{
		$im = new Imagick($source);
		$count = $im->getNumberImages();

		for($i = 0; $i < $count; $i++)
		{
			$sourceImage = new Imagick();
			$sourceImage->readImage($source."[$i]");

			// put on top of white background
			$image = new Imagick();
			$image->newImage($this->width, $this->height, "white");
			$image->compositeimage($sourceImage, Imagick::COMPOSITE_OVER, 0, 0);
			$image->setImageFormat('jpg');
			// $image->setResolution(144, 144);

			$image->setImageUnits(Imagick::RESOLUTION_PIXELSPERINCH);
			$image->setImageCompression(Imagick::COMPRESSION_JPEG);
			$image->setImageCompressionQuality(75);

			$image->writeImage(sprintf('%s/temp/%s_%s.jpg', dirname($source), basename($source), $i));
			$image->clear();
			$image->destroy();

		}
	}
}

