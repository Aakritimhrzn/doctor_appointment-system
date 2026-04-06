<?php
require_once '../config/db.php';
requireAdminLogin();

// Handle status update
if(isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if($action == 'confirm') {
        mysqli_query($conn, "UPDATE appointments SET status='confirmed' WHERE id=$id");
    } elseif($action == 'cancel') {
        mysqli_query($conn, "UPDATE appointments SET status='cancelled' WHERE id=$id");
    } elseif($action == 'delete') {
        mysqli_query($conn, "DELETE FROM appointments WHERE id=$id");
    }
    header("Location: manage_appointments.php");
    exit();
}

// Filter by status
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$where = "";
if($status_filter != 'all') {
    $where = "WHERE a.status = '$status_filter'";
}

$appointments = mysqli_query($conn, "SELECT a.*, p.name as patient_name, p.email, p.mobile, 
                                            d.name as doctor_name, dept.name as dept_name
                                     FROM appointments a 
                                     JOIN patients p ON a.patient_id = p.id 
                                     JOIN doctors d ON a.doctor_id = d.id
                                     JOIN departments dept ON d.department_id = dept.id
                                     $where
                                     ORDER BY a.appointment_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-nav { background: #1e3c72; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .admin-nav a { color: white; text-decoration: none; margin-left: 1rem; }
        .admin-container { padding: 2rem; max-width: 1400px; margin: 0 auto; }
        .filter-bar { margin: 1rem 0; }
        .filter-bar a { padding: 5px 10px; background: #f0f0f0; text-decoration: none; margin-right: 5px; border-radius: 3px; }
        .filter-bar a.active { background: #1e3c72; color: white; }
        table { width: 100%; background: white; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #1e3c72; color: white; }
        .btn-confirm { background: #28a745; color: white; padding: 3px 8px; text-decoration: none; border-radius: 3px; }
        .btn-cancel { background: #ffc107; color: #333; padding: 3px 8px; text-decoration: none; border-radius: 3px; }
        .btn-delete { background: #dc3545; color: white; padding: 3px 8px; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <h2 style="color:white;">MediCare Admin</h2>
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="manage_appointments.php">Appointments</a>
            <a href="manage_doctors.php">Doctors</a>
            <a href="manage_departments.php">Departments</a>
            <a href="manage_patients.php">Patients</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="admin-container">
        <h1>Manage Appointments</h1>
        
        <div class="filter-bar">
            <a href="?status=all" class="<?php echo $status_filter=='all'?'active':''; ?>">All</a>
            <a href="?status=pending" class="<?php echo $status_filter=='pending'?'active':''; ?>">Pending</a>
            <a href="?status=confirmed" class="<?php echo $status_filter=='confirmed'?'active':''; ?>">Confirmed</a>
            <a href="?status=cancelled" class="<?php echo $status_filter=='cancelled'?'active':''; ?>">Cancelled</a>
        </div>
        
         <table>
            <thead>
                <tr><th>ID</th><th>Patient</th><th>Doctor</th><th>Department</th><th>Date</th><th>Time</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($appointments)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['patient_name']; ?><br><small><?php echo $row['email']; ?></small></td>
                    <td><?php echo $row['doctor_name']; ?></td>
                    <td><?php echo $row['dept_name']; ?></td>
                    <td><?php echo $row['appointment_date']; ?></td>
                    <td><?php echo $row['appointment_time']; ?></td>
                    <td>
                        <span style="background: <?php echo $row['status']=='confirmed'?'#28a745':($row['status']=='pending'?'#ffc107':'#dc3545'); ?>; color: <?php echo $row['status']=='pending'?'#333':'white'; ?>; padding: 3px 8px; border-radius: 3px;">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if($row['status'] == 'pending'): ?>
                            <a href="?action=confirm&id=<?php echo $row['id']; ?>" class="btn-confirm" onclick="return confirm('Confirm this appointment?')">Confirm</a>
                        <?php endif; ?>
                        <?php if($row['status'] != 'cancelled' && $row['status'] != 'cancelled'): ?>
                            <a href="?action=cancel&id=<?php echo $row['id']; ?>" class="btn-cancel" onclick="return confirm('Cancel this appointment?')">Cancel</a>
                        <?php endif; ?>
                        <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Permanently delete this appointment?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
         </table>
    </div>
</body>
</html>