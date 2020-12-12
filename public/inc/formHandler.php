<?php

$successful_submit = false;
$errors = [];

$full_name = '';
$email = '';
$message = '';
$phone = '';

if( !isset( $_POST['full_name']) || !$_POST['full_name'] ){
	$errors['full_name'] = 'You must enter your full name.';
} else {
	$full_name = filter_var( $_POST['full_name'], FILTER_SANITIZE_STRING );
}

if( !isset( $_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !$_POST['email']  ){
	$errors['email'] = 'You must enter a valid email address.';
} else {
	$email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL );
}

if( !isset( $_POST['message']) || !$_POST['message'] ){
	$errors['message'] = 'You must enter a message.';
} else {
	$message = filter_var( $_POST['message'], FILTER_SANITIZE_STRING );
}

if( isset($_POST['phone']) ){
	$phone = filter_var( $_POST['phone'], FILTER_SANITIZE_STRING );
}

if( empty($errors) ){



	// make DB connection
	$link = mysqli_connect("localhost", "root", "root", "contact_form_cc");
	if($link === false){
    die( 'Error with DB connection: ' . mysqli_connect_error() );
	}

	$insert_sql = 'INSERT INTO entries (full_name, email, email_message, phone) VALUES (?, ?, ?, ?)';

	if( $stmt = mysqli_prepare($link, $insert_sql) ){
		mysqli_stmt_bind_param($stmt, 'ssss', $full_name, $email, $message, $phone);
		mysqli_stmt_execute($stmt);

	} else{
			die( 'Error with prepared query: ' . mysqli_error($link) );
	}

	mysqli_stmt_close($stmt);
	mysqli_close($link);



	// send email
	$to = 'guy-smiley@example.com';
	$header_to = 'To: Guy Smiley <'.$to.'>';
	$header_from = 'From: '.$full_name.' <'.$email.'>';
	$subject = 'New Form Submission';

	$headers = [];
	$headers[] = $header_to;
	$headers[] = $header_from;
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=iso-8859-1';

	mail( $to, $subject, $message, implode("\r\n", $headers) );

	$successful_submit = true;

}

$response = [
	'successful_submit' => true,
	'errors' => $errors,
];

echo json_encode($response);
die();
