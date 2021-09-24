<?php
// Alessandro Bonomo 5D informatica a.s. 2018-2019
// Nome dell' host
$hostMySql = "localhost";
// Username dell'utente in connessione
$userMySql = "";
// Password dell'utente
$passwordMySql = "";
$dbMySql = "mpa2";
// Stringa di connessione al DBMS
$mysqli = mysqli_connect($hostMySql, $userMySql, $passwordMySql, $dbMySql) or die("Connessione fallita");

?>

