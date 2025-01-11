<?php
$servername = "localhost";
$username = "root"; // jouw MySQL username
$password = ""; // jouw MySQL password
$dbname = "gebruikers_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Verbinding mislukt: " . $e->getMessage();
}
?>
