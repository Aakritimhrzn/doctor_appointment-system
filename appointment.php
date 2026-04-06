<?php
require_once __DIR__ . '/config/db.php';
requireLogin();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = intval($_POST['doctor_id']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);
    $patient_id = $_SESSION['patient_id'];
    
    // Check if time slot is available (prevents double booking)
    if (!isTimeSlotAvailable($conn, $doctor_id, $appointment_date, $appointment_time)) {
        $error = "❌ This time slot is already booked! Please select another time or date.";
    } else {
        // Check if patient already has appointment with same doctor on same date
        $check = "SELECT * FROM appointments WHERE patient_id = $patient_id AND doctor_id = $doctor_id AND appointment_date = '$appointment_date'";
        $check_result = mysqli_query($conn, $check);
        
        if(mysqli_num_rows($check_result) > 0) {
            $error = "You already have an appointment with this doctor on this date!";
        } else {
            $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status) 
                      VALUES ($patient_id, $doctor_id, '$appointment_date', '$appointment_time', 'pending')";
            
            if(mysqli_query($conn, $query)) {
                $message = "✅ Appointment booked successfully! Status: Pending confirmation.";
            } else {
                if(mysqli_errno($conn) == 1062) {
                    $error = "❌ This time slot is already booked! Please select another time or date.";
                } else {
                    $error = "Error booking appointment: " . mysqli_error($conn);
                }
            }
        }
    }
}

// Get departments for dropdown
$dept_query = "SELECT * FROM departments";
$dept_result = mysqli_query($conn, $dept_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - MediCare</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
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
        <div class="form-container">
            <h2>Book a Doctor Appointment</h2>
            
            <?php if($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" onsubmit="return validateAppointmentForm()">
                <div class="form-group">
                    <label>Select Department</label>
                    <select id="department" name="department" required onchange="loadDoctorsByDepartment()">
                        <option value="">Select Department</option>
                        <?php while($dept = mysqli_fetch_assoc($dept_result)): ?>
                            <option value="<?php echo $dept['id']; ?>"><?php echo $dept['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Select Doctor</label>
                    <select id="doctor" name="doctor_id" required>
                        <option value="">First select department</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Appointment Date</label>
                    <input type="date" id="appointment_date" name="appointment_date" required>
                </div>
                
                <div class="form-group">
                    <label>Appointment Time</label>
                    <select id="appointment_time" name="appointment_time" required>
                        <option value="">Select Time</option>
                        <option value="09:00 AM">09:00 AM</option>
                        <option value="10:00 AM">10:00 AM</option>
                        <option value="11:00 AM">11:00 AM</option>
                        <option value="02:00 PM">02:00 PM</option>
                        <option value="03:00 PM">03:00 PM</option>
                        <option value="04:00 PM">04:00 PM</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">Book Appointment</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2026 MediCare - Doctor Appointment System. All rights reserved.</p>
    </footer>
</body>
</html>