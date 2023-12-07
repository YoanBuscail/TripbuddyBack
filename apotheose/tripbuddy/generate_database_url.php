<?php
$password = "4a)jd6pc2469#vB";
$encodedPassword = urlencode($password);

$url = "mysql://root:" . $encodedPassword . "@127.0.0.1:3306/Tripbuddy?serverVersion=8&charset=utf8mb4";

echo "DATABASE_URL=\"$url\"";
?>
