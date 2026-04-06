<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments - MediCare</title>
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
                <li><a href="appointment.php">📅 Book Appointment</a></li>
                <li><a href="my_bookings.php">📋 My Bookings</a></li>
                <li><a href="scheduled_sessions.php">✅ Scheduled</a></li>
                <li><a href="settings.php">⚙️ Settings</a></li>
                <li><a href="logout.php">🚪 Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">🔑 Login</a></li>
                <li><a href="register.php">📝 Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="container">
        <h1>Our Departments</h1>
        <div class="department-grid">
            <?php
            $query = "SELECT * FROM departments";
            $result = mysqli_query($conn, $query);
            while($dept = mysqli_fetch_assoc($result)):
            ?>
            <div class="card">
                <div class="card-content">
                    <h3><?php echo $dept['name']; ?></h3>
                    <p><?php echo $dept['description']; ?></p>
                    <br>
                    <a href="doctors.php?dept=<?php echo $dept['id']; ?>" class="btn-primary" style="display: inline-block; margin-top: 10px;">View Doctors</a>
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