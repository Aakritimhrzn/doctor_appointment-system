<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors - MediCare</title>
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
            <?php if(isLoggedIn()): ?>
                <li><a href="appointment.php">Book Appointment</a></li>
                <li><a href="my_bookings.php">My Bookings</a></li>
                <li><a href="scheduled_sessions.php">Scheduled</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="container">
        <h1>Our Doctors</h1>
        <div class="doctor-grid">
            <?php
            $dept_filter = isset($_GET['dept']) ? "WHERE department_id = " . intval($_GET['dept']) : "";
            $query = "SELECT d.*, dept.name as dept_name FROM doctors d 
                      JOIN departments dept ON d.department_id = dept.id 
                      $dept_filter";
            $result = mysqli_query($conn, $query);
            while($doctor = mysqli_fetch_assoc($result)):
            ?>
            <div class="card">
                <img src="<?php echo $doctor['photo_url']; ?>" alt="<?php echo $doctor['name']; ?>">
                <div class="card-content">
                    <h3><?php echo $doctor['name']; ?></h3>
                    <p><strong>Department:</strong> <?php echo $doctor['dept_name']; ?></p>
                    <p><strong>Qualification:</strong> <?php echo $doctor['qualification']; ?></p>
                    <p><strong>Available:</strong> <?php echo $doctor['available_days']; ?></p>
                    <p><strong>Time:</strong> <?php echo $doctor['available_time']; ?></p>
                    <?php if(isLoggedIn()): ?>
                        <a href="appointment.php?doctor=<?php echo $doctor['id']; ?>" class="btn-primary" style="display: inline-block; margin-top: 10px;">Book Appointment</a>
                    <?php else: ?>
                        <a href="login.php" class="btn-primary" style="display: inline-block; margin-top: 10px;">Login to Book</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2026 MediCare - Doctor Appointment System. All rights reserved.</p>
    </footer>
</body>
</html>