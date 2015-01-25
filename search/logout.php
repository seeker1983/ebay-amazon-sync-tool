<?php
session_start();

unset($_SESSION['username']);
session_destroy();

//sleep(1);
header("location: index.php");
exit();
?>