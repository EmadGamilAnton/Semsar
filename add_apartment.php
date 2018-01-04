<?php
require "init.php";

$response = array('error' =>false , 'message' =>'تمام الشقة اتضافت');

if(isset($_POST['apartment']))
{
    $apartment = new Apartments();
    $data_recieved  = json_decode($_POST['apartment'] , false);
    $owner_id       = $data_recieved->owner_id;
    $rooms_number   = $data_recieved->rooms_number;
    $city           = $data_recieved->city;
    $address        = $data_recieved->address;
    $price          = $data_recieved->price;
    $description    = $data_recieved->description;
    $floor          = $data_recieved->floor;
    $gender         = $data_recieved->gender;
    
    $apartment->set_owner_id($owner_id);
    $apartment->set_floor($floor);
    $apartment->set_rooms_number($rooms_number);
    $apartment->set_address($address);
    $apartment->set_price($price);
    $apartment->set_city($city);
    $apartment->set_description($description);
    $apartment->set_gender($gender);
    
    if(!$apartment->add_apartment())
    {
    	$response['error']		= true;
    	$response['message']	= $apartment->error;
    }
    echo json_encode($response);
}