<?php

if( isset( $_POST['full_name'], $_POST['email'], $_POST['message'] ) ){
	require_once 'formHandler.class.php';

	$formHandler = new FormHandler( $_POST );
	$response = $formHandler->processForm();

	echo $response;

}

die();
