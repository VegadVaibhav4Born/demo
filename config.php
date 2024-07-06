<?php

$server = "localhost";
$user="root";
$pass = "";
$db = "php_livechat";

$con = mysqli_connect($server,$user,$pass,$db);
if($con)
{
    // echo "Connction Success";
}
else{
    echo "db connnection Failed";
}

?>