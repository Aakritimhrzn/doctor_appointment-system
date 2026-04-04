<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - MediCare</title>
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
        <h1>About MediCare</h1>
        <div style="background: white; padding: 2rem; border-radius: 10px; margin-top: 2rem;">
            <p style="font-size: 1.1rem; line-height: 1.8;">MediCare is a leading healthcare provider committed to delivering exceptional medical care with compassion and innovation. Founded in 2010, we have grown into a trusted name in the healthcare industry.</p>
            <br>
            <h3>Our Mission</h3>
            <p>To provide accessible, affordable, and quality healthcare to every patient who walks through our doors.</p>
            <br>
            <h3>Our Vision</h3>
            <p>To be the most patient-centric healthcare system in the country, leveraging technology for better health outcomes.</p>
            <br>
            <h3>Why Us?</h3>
            <ul style="margin-left: 2rem;">
                <li>100+ experienced doctors</li>
                <li>State-of-the-art facilities</li>
                <li>24/7 emergency services</li>
                <li>Affordable treatment plans</li>
                <li>Online appointment booking</li>
            </ul>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2026 MediCare - Doctor Appointment System. All rights reserved.</p>
    </footer>
</body>
</html>