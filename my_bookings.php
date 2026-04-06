<?php
include 'config/db.php';
requireLogin();

$patient_id = $_SESSION['patient_id'];

// Cancel appointment
if(isset($_GET['cancel'])) {
    $appointment_id = intval($_GET['cancel']);
    $update = "UPDATE appointments SET status = 'cancelled' WHERE id = $appointment_id AND patient_id = $patient_id";
    mysqli_query($conn, $update);
    header("Location: my_bookings.php");
    exit();
}

// Reschedule
if(isset($_POST['reschedule'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $new_date = mysqli_real_escape_string($conn, $_POST['new_date']);
    $new_time = mysqli_real_escape_string($conn, $_POST['new_time']);
    
    $update = "UPDATE appointments SET appointment_date = '$new_date', appointment_time = '$new_time', status = 'pending' 
               WHERE id = $appointment_id AND patient_id = $patient_id";
    mysqli_query($conn, $update);
    header("Location: my_bookings.php");
    exit();
}

$query = "SELECT a.*, d.name as doctor_name, dept.name as dept_name 
          FROM appointments a 
          JOIN doctors d ON a.doctor_id = d.id 
          JOIN departments dept ON d.department_id = dept.id 
          WHERE a.patient_id = $patient_id 
          ORDER BY a.appointment_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - MediCare</title>
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
        <h1>My Appointments</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['doctor_name']; ?></td>
                        <td><?php echo $row['dept_name']; ?></td>
                        <td><?php echo $row['appointment_date']; ?></td>
                        <td><?php echo $row['appointment_time']; ?></td>
                        <td>
                            <?php
                            $status_colors = [
                                'confirmed' => '#28a745',
                                'pending' => '#ffc107',
                                'cancelled' => '#dc3545'
                            ];
                            $status_text = [
                                'confirmed' => '✅ Confirmed',
                                'pending' => '⏳ Pending',
                                'cancelled' => '❌ Cancelled'
                            ];
                            $color = $status_colors[$row['status']] ?? '#6c757d';
                            $text = $status_text[$row['status']] ?? ucfirst($row['status']);
                            ?>
                            <span style="background: <?php echo $color; ?>; color: <?php echo $row['status'] == 'pending' ? '#333' : 'white'; ?>; padding: 3px 8px; border-radius: 3px;">
                                <?php echo $text; ?>
                            </span>
                        </td>
                        <td>
                            <?php if($row['status'] != 'cancelled'): ?>
                                <button onclick="showReschedule(<?php echo $row['id']; ?>, '<?php echo $row['appointment_date']; ?>', '<?php echo $row['appointment_time']; ?>')" class="btn-reschedule">Reschedule</button>
                                <a href="?cancel=<?php echo $row['id']; ?>" onclick="return confirm('Cancel this appointment?')" class="btn-cancel">Cancel</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($result) == 0): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No appointments found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Reschedule Modal -->
    <div id="rescheduleModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
        <div style="background:white; max-width:400px; margin:100px auto; padding:20px; border-radius:10px;">
            <h3>Reschedule Appointment</h3>
            <form method="POST">
                <input type="hidden" name="appointment_id" id="reschedule_id">
                <div class="form-group">
                    <label>New Date</label>
                    <input type="date" name="new_date" id="new_date" required>
                </div>
                <div class="form-group">
                    <label>New Time</label>
                    <select name="new_time" id="new_time" required>
                        <option value="09:00 AM">09:00 AM</option>
                        <option value="10:00 AM">10:00 AM</option>
                        <option value="11:00 AM">11:00 AM</option>
                        <option value="02:00 PM">02:00 PM</option>
                        <option value="03:00 PM">03:00 PM</option>
                        <option value="04:00 PM">04:00 PM</option>
                    </select>
                </div>
                <button type="submit" name="reschedule" class="btn-submit">Update Appointment</button>
                <button type="button" onclick="closeModal()" style="margin-top:10px;">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function showReschedule(id, date, time) {
            document.getElementById('reschedule_id').value = id;
            document.getElementById('new_date').value = date;
            document.getElementById('new_time').value = time;
            document.getElementById('rescheduleModal').style.display = 'block';
            document.getElementById('new_date').min = new Date().toISOString().split("T")[0];
        }
        
        function closeModal() {
            document.getElementById('rescheduleModal').style.display = 'none';
        }
    </script>

    <footer class="footer">
        <p>&copy; 2026 MediCare - Doctor Appointment System. All rights reserved.</p>
    </footer>
</body>
</html>