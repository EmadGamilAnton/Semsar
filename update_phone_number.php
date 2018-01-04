<?php
require "init.php";

if(isset($_POST['phone_number']) && isset($_POST['id']))
{
	$response = array('error'=>false ,'message'=>'تمام الرقم اتغير');

	$user = new Users();
	$user->set_phone_number($_POST['phone_number']);
	if(!$user->update_phone_number($_POST['id']))
	{
		$response['error'] 		= true;
		$response['message'] 	= $user->error; 
	}

	echo json_encode($response);
}