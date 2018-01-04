<?php
require "init.php";

if(isset($_POST['id']))
{
	$apartment_data = Apartments::get_apartment_by_id($_POST['id']);

	echo json_encode($apartment_data);
}