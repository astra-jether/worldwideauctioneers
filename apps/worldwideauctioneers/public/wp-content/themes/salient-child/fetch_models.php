<?php
// fetch_models.php

header('Content-Type: application/json');


$make = $_GET['make']; // Get the selected make from the AJAX request

// Database connection variables
$hostname = "mysql24.ezhostingserver.com";
$username = "wa_myadmin";
$password = "W4_my@hm!N";
$dbname = "wa_online_db";

// Create connection
$conn = new mysqli($hostname, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT DISTINCT smodel FROM t_inventory WHERE smake = '".$make."' AND ncategoryid = 1 AND smodel != '' ORDER BY smodel";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $make);
$stmt->execute();
$result = $stmt->get_result();

$models = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $models[] = $row['smodel'];
    }
}

$conn->close();

echo json_encode($models);
?>
