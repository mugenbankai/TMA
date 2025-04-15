<?php

setcookie('mail','',time());

session_start();
$_SESSION['mail'] = array();
session_destroy();

header('Location: index.php');

?>