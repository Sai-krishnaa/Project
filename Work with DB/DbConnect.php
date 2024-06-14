<?php
$serverName = 'localhost';
$userName = 'root';
$password = '';
$databaseName = 'election_voting';
$con = mysqli_connect($serverName, $userName, $password, $databaseName);
if ($con) {
    echo "connection done successfully";
} else {
    echo "Sorry the conncetion not done!!";
}