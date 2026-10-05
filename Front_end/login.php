<?php

require_once __DIR__ . '/../Back_end/config/database.php';


$cpf= $_POST['CPF'];
$senha=$_POST['senha'];

$res = mysqli_query($con,"SELECT * FROM usuarios WHERE cpf = '$cpf' AND senha = '$senha'");

?>
