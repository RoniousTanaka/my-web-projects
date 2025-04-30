<?php
session_start();
if (!isset(\['company_logged_in'])) {
  header('Location: login.php');
  exit();
}
?>
