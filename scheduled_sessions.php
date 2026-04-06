<?php
include 'config/db.php';
requireLogin();

$patient_id = $_SESSION['patient_id'];

$query = "SELECT a.*, d.name as doctor_name, dept.name as dept_name, d.qualification 
          FROM appointments a 
          JOIN doctors d ON a.doctor_id = d.id 
          JOIN departments dept ON d.department_id = dept.id 
          WHERE a.patient_id = $patient_id 
          AND a.status = 'confirmed' 
          AND a.appointment_date >= CURDATE()
          ORDER BY a.appointment_date ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Sessions - MediCare</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo"><h2>Medi<span>Care</span></h2></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="departments.php">Departments</a></li>
            <li><a href="doctors.php">Doctors</a></li>
            <li><a href="appointment.php">📅 Book Appointment</a></li>
            <li><a href="my_bookings.php">📋 My Bookings</a></li>
            <li><a href="scheduled_sessions.php">✅ Scheduled</a></li>
            <li><a href="settings.php">⚙️ Settings</a></li>
            <li><a href="logout.php">🚪 Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Your Scheduled Sessions</h1>
        <div class="doctor-grid">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <div class="card-content">
                        <h3>Dr. <?php echo $row['doctor_name']; ?></h3>
                        <p><strong>Department:</strong> <?php echo $row['dept_name']; ?></p>
                        <p><strong>Qualification:</strong> <?php echo $row['qualification']; ?></p>
                        <p><strong>📅 Date:</strong> <?php echo date('F j, Y', strtotime($row['appointment_date'])); ?></p>
                        <p><strong>⏰ Time:</strong> <?php echo $row['appointment_time']; ?></p>
                        <p><strong>✅ Status:</strong> Confirmed</p>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 2rem; grid-column: 1/-1;">
                    <p>📅 No upcoming scheduled sessions.</p>
                    <p><small>Only <strong>confirmed</strong> appointments appear here. Your pending appointments will show once confirmed by admin.</small></p>
                    <a href="appointment.php" class="btn-primary" style="display: inline-block; margin-top: 1rem;">Book an Appointment</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2026 MediCare - Doctor Appointment System. All rights reserved.</p>
    </footer>
</body>
</html>