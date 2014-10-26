<?php
require_once __DIR__ . '/../autoloader.php';

SiteUser::destroy();
//session_destroy();
//session_start();
header('Location:../index.php');
?>