<?php
require "init.php";

if(isset($_POST['phone_number']))
{
	$user = Users::get_user_by_phone_number($_POST['phone_number']);

	echo json_encode($user);

}

if(isset($_POST['id']))
{
	$user = Users::get_user_by_id($_POST['id']);

	echo json_encode($user);
	
}