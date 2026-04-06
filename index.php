<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment System - Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <h2>Medi<span>Care</span></h2>
        </div>
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
                <li><a href="logout.php">🚪 Logout (<?php echo getPatientName($conn, $_SESSION['patient_id']); ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php">🔑 Login</a></li>
                <li><a href="register.php">📝 Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <section class="hero">
        <h1>Welcome to MediCare</h1>
        <p>Your Health, Our Priority - Book Appointments with Top Doctors</p>
        <?php if(isLoggedIn()): ?>
            <a href="appointment.php" class="btn-primary">Make Appointment</a>
        <?php else: ?>
            <a href="register.php" class="btn-primary">Get Started</a>
        <?php endif; ?>
    </section>

    <div class="container">
        <h2>Why Choose MediCare?</h2>
        <div class="department-grid" style="margin-top: 2rem;">
            <div class="card">
                <div class="card-content">
                    <h3>🏥 Expert Doctors</h3>
                    <p>Board-certified specialists with years of experience</p>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <h3>📅 Easy Booking</h3>
                    <p>Schedule appointments online in just a few clicks</p>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <h3>💊 Quality Care</h3>
                    <p>Patient-first approach with modern facilities</p>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <h3>🕒 24/7 Support</h3>
                    <p>Emergency services and round-the-clock assistance</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2026 MediCare - Doctor Appointment System. All rights reserved.</p>
        <p style="margin-top: 10px;">
            <a href="admin/login.php" style="color: white; text-decoration: none; opacity: 0.7;">👑 Admin Login</a>
        </p>
    </footer>
</body>
</html>