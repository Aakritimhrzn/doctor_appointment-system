<?php
require_once __DIR__ . '/config/db.php';

if(isset($_GET['doctor_id']) && isset($_GET['date']) && isset($_GET['time'])) {
    $doctor_id = intval($_GET['doctor_id']);
    $date = mysqli_real_escape_string($conn, $_GET['date']);
    $time = mysqli_real_escape_string($conn, $_GET['time']);
    
    $check = "SELECT id FROM appointments 
              WHERE doctor_id = $doctor_id 
              AND appointment_date = '$date' 
              AND appointment_time = '$time'
              AND status != 'cancelled'";
    $result = mysqli_query($conn, $check);
    
    $available = mysqli_num_rows($result) == 0;
    
    header('Content-Type: application/json');
    echo json_encode(['available' => $available]);
}
?>