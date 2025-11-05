<?php
session_start();
$_SESSION['cek'] = 'Halo dari test1!';
header("Location: test2.php");
exit;
