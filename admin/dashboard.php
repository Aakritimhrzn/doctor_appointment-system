<?php
require_once '../config/db.php';
requireAdminLogin();

// Get statistics
$total_patients = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM patients"))['count'];
$total_doctors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM doctors"))['count'];
$total_departments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM departments"))['count'];
$total_appointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments"))['count'];

$pending_appointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE status='pending'"))['count'];
$confirmed_appointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE status='confirmed'"))['count'];
$cancelled_appointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE status='cancelled'"))['count'];

// Recent appointments
$recent_appointments = mysqli_query($conn, "SELECT a.*, p.name as patient_name, d.name as doctor_name 
                                            FROM appointments a 
                                            JOIN patients p ON a.patient_id = p.id 
                                            JOIN doctors d ON a.doctor_id = d.id 
                                            ORDER BY a.booking_date DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MediCare</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-nav {
            background: #1e3c72;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .admin-nav h2 {
            color: white;
            margin: 0;
        }
        .admin-nav-links {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .admin-nav-links a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .admin-nav-links a:hover {
            background: #2a5298;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #1e3c72;
        }
        .stat-label {
            color: #666;
            margin-top: 0.5rem;
        }
        .stat-pending { border-left: 4px solid #ffc107; }
        .stat-confirmed { border-left: 4px solid #28a745; }
        .stat-cancelled { border-left: 4px solid #dc3545; }
        .admin-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #1e3c72;
            color: white;
        }
        .btn-small {
            padding: 3px 8px;
            font-size: 12px;
            margin: 2px;
        }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <h2>MediCare Admin Panel</h2>
        <div class="admin-nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage_appointments.php">Appointments</a>
            <a href="manage_doctors.php">Doctors</a>
            <a href="manage_departments.php">Departments</a>
            <a href="manage_patients.php">Patients</a>
            <a href="logout.php">Logout (<?php echo getAdminName($conn, $_SESSION['admin_id']); ?>)</a>
        </div>
    </nav>

    <div class="admin-container">
        <h1>Dashboard Overview</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_patients; ?></div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_doctors; ?></div>
                <div class="stat-label">Total Doctors</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_departments; ?></div>
                <div class="stat-label">Departments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_appointments; ?></div>
                <div class="stat-label">Total Appointments</div>
            </div>
            <div class="stat-card stat-pending">
                <div class="stat-number"><?php echo $pending_appointments; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card stat-confirmed">
                <div class="stat-number"><?php echo $confirmed_appointments; ?></div>
                <div class="stat-label">Confirmed</div>
            </div>
            <div class="stat-card stat-cancelled">
                <div class="stat-number"><?php echo $cancelled_appointments; ?></div>
                <div class="stat-label">Cancelled</div>
            </div>
        </div>
        
        <h2>Recent Appointments</h2>
        <table>
            <thead>
                <tr><th>Patient</th><th>Doctor</th><th>Date</th><th>Time</th><th>Status</th><th>Booked On</th></tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($recent_appointments)): ?>
                <tr>
                    <td><?php echo $row['patient_name']; ?></td>
                    <td><?php echo $row['doctor_name']; ?></td>
                    <td><?php echo $row['appointment_date']; ?></td>
                    <td><?php echo $row['appointment_time']; ?></td>
                    <td><span style="background: <?php echo $row['status']=='confirmed'?'#28a745':($row['status']=='pending'?'#ffc107':'#dc3545'); ?>; color: white; padding: 3px 8px; border-radius: 3px;"><?php echo ucfirst($row['status']); ?></span></td>
                    <td><?php echo $row['booking_date']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>