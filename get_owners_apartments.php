<?php
require "init.php";

if(isset($_POST['id']))
{
	$apartments = Apartments::get_owner_apartments($_POST['id']);

	echo json_encode($apartments);
}