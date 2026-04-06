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

// ============ PATIENT FUNCTIONS ============

function isLoggedIn() {
    return isset($_SESSION['patient_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function getPatientName($conn, $patient_id) {
    $query = "SELECT name FROM patients WHERE id = $patient_id";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['name'];
    }
    return "Patient";
}

// ============ ADMIN FUNCTIONS ============

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header("Location: admin/login.php");
        exit();
    }
}

function getAdminName($conn, $admin_id) {
    $query = "SELECT username FROM admins WHERE id = $admin_id";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['username'];
    }
    return "Admin";
}

// ============ BOOKING FUNCTIONS ============

function isTimeSlotAvailable($conn, $doctor_id, $appointment_date, $appointment_time) {
    $check = "SELECT id FROM appointments 
              WHERE doctor_id = $doctor_id 
              AND appointment_date = '$appointment_date' 
              AND appointment_time = '$appointment_time'
              AND status != 'cancelled'";
    $result = mysqli_query($conn, $check);
    return mysqli_num_rows($result) == 0;
}

function getAppointmentCounts($conn) {
    $counts = [];
    $query = "SELECT status, COUNT(*) as count FROM appointments GROUP BY status";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)) {
        $counts[$row['status']] = $row['count'];
    }
    return $counts;
}
?>