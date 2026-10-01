<?php

$host = getenv('MYSQL_HOST') ;
$dbname = getenv('MYSQL_DATABASE') ;
$username = getenv('MYSQL_USER') ;
$password = getenv('MYSQL_PASSWORD');

$con = mysqli_connect($host,$username,$password,$dbname);
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

?>