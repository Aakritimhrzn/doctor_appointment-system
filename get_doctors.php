<?php
include 'config/db.php';

if(isset($_GET['department_id'])) {
    $dept_id = intval($_GET['department_id']);
    $query = "SELECT id, name, qualification FROM doctors WHERE department_id = $dept_id";
    $result = mysqli_query($conn, $query);
    
    $doctors = [];
    while($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($doctors);
}
?>