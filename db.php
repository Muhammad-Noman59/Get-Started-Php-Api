<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "first_php";

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($mysqli->connect_errno) {

    $response = array(
        "success" => false,
        "message" => "invaid database connection details"
    );

    echo json_encode($response);
    die();
}

?>