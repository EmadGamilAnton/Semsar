<?php
require "init.php";

if(isset($_POST['id']))
{
	$response = array('error' =>false , 'message' => 'Profile Picture Updated Successfully !');

	$photo = new Photos();
	if(!$photo->set_profile_pic($_POST['id']))
	{
		$response['error'] 		= true;
		$response['message'] 	= 'Uploading Error Please Try Again ...';
	}
	echo json_encode($response);
}