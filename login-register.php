<?php
require "init.php";

$response       = array();
$success_message= "يا مرحب يا مرحب";

$data_recieved  = json_decode(file_get_contents("php://input"));
$phone_number   = $data_recieved->user->phone_number;
$password       = $data_recieved->user->password;
$full_name      = $data_recieved->user->full_name;

if($data_recieved->operation == 'register')
{
	$user = new Users();
	$user->set_full_name($full_name);
	$user->set_password($password);
	$user->set_phone_number($phone_number);
	$result = $user->create_user();
	if($result)
	{
	    $response['error'] 		= false;
		$response['message'] 	= $success_message; 
		$response['user']       = $result;
	}
    else
    {
        $response['error'] 		= true;
		$response['message'] 	= $user->error; 
		$response['user']       = NULL;  
    }
    echo json_encode($response);
}

if($data_recieved->operation == 'login')
{
	$user = new Users();
	$user->set_password($password);
	$user->set_phone_number($phone_number);
	$result = $user->verify_user();
	if($result)
	{
	    $response['error'] 		= false;
		$response['message'] 	= $success_message; 
		$response['user']       = $result;
	}
	else
	{
		$response['error'] 		= true;
		$response['message'] 	= $user->error; 
		$response['user']       = NULL;
	}
	echo json_encode($response);
}