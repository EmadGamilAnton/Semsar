<?php
require "init.php";

if(isset($_POST['id']))
{
    Apartments::increment_apartment_views($_POST['id']);
}