<?php
session_start();
echo "Le compte ".$_SESSION["Email"]." a été déconnecté...";
session_destroy();
header("refresh:3;url=connexion.php");
?>