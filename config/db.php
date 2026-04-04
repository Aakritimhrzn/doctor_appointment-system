<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'doctor_appointment_db';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['patient_id']);
}

// Function to redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Function to get patient name
function getPatientName($conn, $patient_id) {
    $query = "SELECT name FROM patients WHERE id = $patient_id";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['name'];
    }
    return "Patient";
}
?>