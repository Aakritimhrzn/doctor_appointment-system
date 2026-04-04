<?php
include 'config/db.php';
requireLogin();

$patient_id = $_SESSION['patient_id'];
$message = '';
$error = '';

// Get current user data
$query = "SELECT * FROM patients WHERE id = $patient_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = $_POST['password'];
    
    if($password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update = "UPDATE patients SET name='$name', mobile='$mobile', password='$hashed_password' WHERE id=$patient_id";
    } else {
        $update = "UPDATE patients SET name='$name', mobile='$mobile' WHERE id=$patient_id";
    }
    
    if(mysqli_query($conn, $update)) {
        $message = "Profile updated successfully!";
        $_SESSION['patient_name'] = $name;
        // Refresh data
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);
    } else {
        $error = "Error updating profile";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - MediCare</title>
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
            <li><a href="appointment.php">Book Appointment</a></li>
            <li><a href="my_bookings.php">My Bookings</a></li>
            <li><a href="scheduled_sessions.php">Scheduled</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="form-container">
            <h2>Profile Settings</h2>
            
            <?php if($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Email (Cannot be changed)</label>
                    <input type="email" value="<?php echo $user['email']; ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="tel" name="mobile" value="<?php echo $user['mobile']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>New Password (Leave blank to keep current)</label>
                    <input type="password" name="password" placeholder="Enter new password">
                </div>
                
                <button type="submit" class="btn-submit">Update Profile</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2026 MediCare - Doctor Appointment System. All rights reserved.</p>
    </footer>
</body>
</html>