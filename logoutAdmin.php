<?php
session_start();
session_destroy();
header('Location: homeAdmin.php');
exit;
?>
