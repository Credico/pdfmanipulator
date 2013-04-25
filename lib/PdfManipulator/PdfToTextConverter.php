<?php

namespace PdfManipulator;

use Exception;


class PdfToTextConverter
{
	public function convert($pdfPath)
	{
		$returnVar = -1;
		$output = array();
		exec('pdf2txt ' . escapeshellarg($pdfPath), $output, $returnVar);
		if($returnVar != 0) {
			throw new Exception('Error while converting pdf to text');
		}
		return implode("\n", $output);
	}
}

