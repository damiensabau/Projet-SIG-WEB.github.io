<?php
session_start();

header("location:log-in.php");

session_destroy();

?>