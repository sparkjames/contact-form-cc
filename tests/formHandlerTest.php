<?php
// class FormHandlerTest extends PHPUnit_Framework_TestCase {

// }


declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FormHandlerTest extends TestCase
{
    /*
    Our first test method: testing if our
    User class can return a full name
     */
    public function testProcessFormReturnsJSON()
    {

        // We need to bring in our User class
        // so that we can access its methods
				require_once '../../../public/inc/formHandler.class.php';

				$sampleResponse = [
					'successful_submit' => false,
					'errors' => [],
				];
				$sampleResponseJSON = json_encode($sampleResponse);

        // Get an instance of the User class
        $formHandler = new FormHandler;

        // Assert if the getFullName() can return the full name 'Femy Babatunde'
        $this->assertEquals($sampleResponseJSON, $formHandler->processForm());
    }
}
