<?
include "master.inc.php";
include('application.php');

unset($_SESSION["user_logged_in"]);
unset($_SESSION["client_id"]);
unset($_SESSION['user_name']);
unset($_SESSION['user_id']);
addMessage("You have been logged out");
redirect('login.php');
?>