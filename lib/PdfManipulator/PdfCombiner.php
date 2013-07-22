<?php
namespace PdfManipulator;

class PdfCombiner
{
	private $pdftkBinary;

	public function __construct($pdftkBinary)
	{
		$this->pdftkBinary = $pdftkBinary;
	}

	/**
	 * @param string Original PDF contents
	 * @param string Background PDF contents
	 * @return string Resulting PDF contents
	 */
	public function addBackground($original, $background)
	{
		$sourceFile = tempnam(sys_get_temp_dir(), 'pdf');
		file_put_contents($sourceFile, $original);

		$backgroundFile = tempnam(sys_get_temp_dir(), 'pdf');
		file_put_contents($backgroundFile, $background);

		$outputFile = tempnam(sys_get_temp_dir(), 'pdf');

		$command = "{$this->pdftkBinary} $sourceFile background $backgroundFile output $outputFile verbose";
		$execOutput = array();
		exec($command, $execOutput);
		$result = file_get_contents($outputFile);
		if(!$result) {
			throw new \RuntimeException("Couldn't add background to PDF: ".PHP_EOL.$command.PHP_EOL.implode(PHP_EOL, $execOutput));
		}

		@unlink($sourceFile);
		@unlink($backgroundFile);
		@unlink($outputFile);

		return $result;
	}

	/**
	 * @param string Original PDF contents
	 * @param string Background PDF contents
	 * @return string Resulting PDF contents
	 */
	public function addBackgroundToFirstPage($original, $background)
	{
		$sourceFile = tempnam(sys_get_temp_dir(), 'pdf');
		file_put_contents($sourceFile, $original);

		// if source file has just 1 page
		$execOutput = array();
		$commandCheck = "{$this->pdftkBinary} $sourceFile dump_data output | grep -i Num";
		exec($commandCheck, $execOutput);

		if(trim(substr($execOutput[0], strpos($execOutput[0], ":") + 1)) == 1) {
			return $this->addBackground($original, $background);
		}

		// create files
		$firstFile = tempnam(sys_get_temp_dir(), 'pdf');
		$catFile = tempnam(sys_get_temp_dir(), 'pdf');
		$outputFile = tempnam(sys_get_temp_dir(), 'pdf');

		// Extract first page
		$commandFirstPage = "{$this->pdftkBinary} $sourceFile cat 1 output $firstFile";
		exec($commandFirstPage, $execOutput);

		// Extract other pages
		$commandCatPage = "{$this->pdftkBinary} $sourceFile cat 2-end output $catFile";
		exec($commandCatPage, $execOutput);

		// add background to first page
		$outputContent = $this->addBackground(file_get_contents($firstFile), $background);

		// combine first page with background and other pages
		$result = $this->catenate(array($outputContent, file_get_contents($catFile)));

		if(!$result) {
			throw new \RuntimeException("Couldn't add background to PDF: ".PHP_EOL.$command.PHP_EOL.implode(PHP_EOL, $execOutput));
		}

		@unlink($sourceFile);
		@unlink($firstFile);
		@unlink($catFile);
		@unlink($outputFile);

		return $result;
	}

	/**
	 * @param array List of original PDF contents
	 * @return string Resulting PDF contents
	 */
	public function catenate(array $originals)
	{
		$sourceFiles = array();
		foreach($originals as $k => $original)
		{
			$sourceFiles[$k] = tempnam(sys_get_temp_dir(), 'pdf');
			file_put_contents($sourceFiles[$k], $original);
		}

		$outputFile = tempnam(sys_get_temp_dir(), 'pdf');

		$command = sprintf("{$this->pdftkBinary} %s cat output $outputFile", implode(' ', $sourceFiles));
		exec($command);
		$result = file_get_contents($outputFile);
		if(!$result) {
			throw new \RuntimeException("Couldn't catenate PDFs");
		}

		foreach($sourceFiles as $sourceFile) {
			@unlink($sourceFile);
		}
		@unlink($outputFile);

		return $result;
	}
}