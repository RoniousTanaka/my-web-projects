<?php
echo'<script> alert ("Hope You enjoyed ")</script>';
session_start();
session_unset();
session_destroy();
header('location:index.php');

?>
