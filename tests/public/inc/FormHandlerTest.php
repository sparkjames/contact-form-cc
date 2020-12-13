<?php
// class FormHandlerTest extends PHPUnit_Framework_TestCase {

// }


declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FormHandlerTest extends TestCase {

	public function testProcessFormReturnsJSON(){

		require_once '../../../public/inc/formHandler.class.php';
		// require_once '../public/inc/formHandler.class.php';

		$sampleResponse = [
			'successful_submit' => false,
			'errors' => [],
		];
		$sampleResponseJSON = json_encode($sampleResponse);

		$formHandler = new FormHandler;

		$this->assertEquals($sampleResponseJSON, $formHandler->processForm());
	}

}
