<?php

require "init.php";
if(isset($_POST['problem']))
{
    $sql = "INSERT INTO reports(report,date) VALUES ('{$_POST['problem']}',now())";
    $database->query($sql);
}
