<?php

require_once __DIR__ . '/../autoloader.php';

$email = $_POST['email'];
$password = $_POST['password'];

$siteUser = new SiteUser($email, $password);
header('Location:../index.php');
?>