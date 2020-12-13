<?php


class FormHandler {
	protected $response = [];
	protected $full_name;
	protected $email;
	protected $message;
	protected $phone;
	protected $errors = [];

	public function __construct( $form_data=[] ){
		if( !isset( $form_data['full_name']) || !$form_data['full_name'] ){
			$errors['full_name'] = 'You must enter your full name.';
		} else {
			$this->full_name = filter_var( $form_data['full_name'], FILTER_SANITIZE_STRING );
		}

		if( !isset( $form_data['email']) || !filter_var($form_data['email'], FILTER_VALIDATE_EMAIL) || !$form_data['email']  ){
			$errors['email'] = 'You must enter a valid email address.';
		} else {
			$this->email = filter_var( $form_data['email'], FILTER_SANITIZE_EMAIL );
		}

		if( !isset( $form_data['message']) || !$form_data['message'] ){
			$errors['message'] = 'You must enter a message.';
		} else {
			$this->message = filter_var( $form_data['message'], FILTER_SANITIZE_STRING );
		}

		if( isset($form_data['phone']) ){
			$this->phone = filter_var( $form_data['phone'], FILTER_SANITIZE_STRING );
		}
	}

	public function processForm(){

		$this->response = [
			'successful_submit' => false,
			'errors' => $this->errors,
		];

		if( empty($this->errors) ){
			$submitted_to_db = $this->submitToDB($this->full_name, $this->email, $this->message, $this->phone);

			$email_sent = $this->sendEmail( $this->full_name, $this->email, $this->message, $this->phone );

			if( $submitted_to_db && $email_sent ){
				$this->response['successful_submit'] = true;
			}
		}

		return json_encode($this->response);

	}

	public function submitToDB($full_name, $email, $message, $phone){

		$success = false;

		// make DB connection
		$link = mysqli_connect("localhost", "root", "root", "contact_form_cc");
		if($link === false){
			die( 'Error with DB connection: ' . mysqli_connect_error() );
		}

		$insert_sql = 'INSERT INTO entries (full_name, email, email_message, phone) VALUES (?, ?, ?, ?)';

		if( $stmt = mysqli_prepare($link, $insert_sql) ){
			mysqli_stmt_bind_param($stmt, 'ssss', $full_name, $email, $message, $phone);
			mysqli_stmt_execute($stmt);
			$success = true;

		}

		mysqli_stmt_close($stmt);
		mysqli_close($link);

		return $success;

	}

	public function sendEmail( $full_name, $email, $message, $phone ){

		$to = 'guy-smiley@example.com';
		$header_to = 'To: Guy Smiley <'.$to.'>';
		$header_from = 'From: '.$full_name.' <'.$email.'>';
		$subject = 'New Form Submission';
		$message = $message . '<br>' . $phone;

		$headers = [];
		$headers[] = $header_to;
		$headers[] = $header_from;
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';

		$successful_send = mail( $to, $subject, $message, implode("\r\n", $headers) );

		return $successful_send;

	}

}
