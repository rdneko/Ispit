<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Omogućava automatsko prikazivanje SQL grešaka (za lakšu dijagnostiku)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$server  = "127.0.0.1";
$username = "root";
$password = "cokolada1"; 
$database = "solarni_paneli";

$conn = new mysqli($server, $username, $password, $database);

// Poboljšana obrada grešaka
if ($conn->connect_error) {
    exit("❌ Greška pri povezivanju sa bazom: " . $conn->connect_error);
}
?>
