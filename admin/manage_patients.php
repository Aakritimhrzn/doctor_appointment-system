<?php
require_once '../config/db.php';
requireAdminLogin();

// Handle Delete
if(isset($_GET['delete'])) { 
    $id = intval($_GET['delete']); 
    mysqli_query($conn, "DELETE FROM patients WHERE id=$id"); 
    header("Location: manage_patients.php"); 
    exit(); 
}

// Handle Edit/Update
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_patient'])) {
    $id = intval($_POST['patient_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    
    $update = "UPDATE patients SET name='$name', email='$email', mobile='$mobile' WHERE id=$id";
    mysqli_query($conn, $update);
    header("Location: manage_patients.php");
    exit();
}

// Get patient for editing
$edit_patient = null;
if(isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM patients WHERE id=$edit_id");
    $edit_patient = mysqli_fetch_assoc($result);
}

// Get all patients
$patients = mysqli_query($conn, "SELECT * FROM patients ORDER BY created_at DESC");

// Get appointment count for each patient
$appointment_counts = [];
$count_query = "SELECT patient_id, COUNT(*) as count FROM appointments GROUP BY patient_id";
$count_result = mysqli_query($conn, $count_query);
while($row = mysqli_fetch_assoc($count_result)) {
    $appointment_counts[$row['patient_id']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients - Admin</title>
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
        .admin-nav a {
            color: white;
            text-decoration: none;
            margin-left: 1rem;
        }
        .admin-nav a:hover {
            background: #2a5298;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .admin-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        .stats-bar {
            background: #f0f7ff;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }
        .stat-item {
            background: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1e3c72;
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
        tr:hover {
            background: #f5f5f5;
        }
        .btn-edit {
            background: #ffc107;
            color: #333;
            padding: 3px 8px;
            text-decoration: none;
            border-radius: 3px;
            margin-right: 5px;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 3px 8px;
            text-decoration: none;
            border-radius: 3px;
        }
        .btn-view {
            background: #17a2b8;
            color: white;
            padding: 3px 8px;
            text-decoration: none;
            border-radius: 3px;
            margin-right: 5px;
        }
        .btn-edit:hover { background: #e0a800; }
        .btn-delete:hover { background: #c82333; }
        .btn-view:hover { background: #138496; }
        
        .edit-form {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border: 1px solid #ddd;
        }
        .edit-form h3 {
            color: #1e3c72;
            margin-bottom: 1rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .form-group {
            margin-bottom: 0;
        }
        .btn-save {
            background: #28a745;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-save:hover {
            background: #218838;
        }
        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 8px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
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
        <h1>Manage Patients</h1>
        
        <!-- Statistics Bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-number"><?php echo mysqli_num_rows($patients); ?></div>
                <div>Total Patients</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo count($appointment_counts); ?></div>
                <div>Active Patients (with bookings)</div>
            </div>
        </div>
        
        <!-- Edit Form (shows when editing) -->
        <?php if($edit_patient): ?>
        <div class="edit-form">
            <h3>✏️ Edit Patient</h3>
            <form method="POST">
                <input type="hidden" name="patient_id" value="<?php echo $edit_patient['id']; ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($edit_patient['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($edit_patient['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="text" name="mobile" value="<?php echo htmlspecialchars($edit_patient['mobile']); ?>" required>
                    </div>
                </div>
                <button type="submit" name="update_patient" class="btn-save">💾 Save Changes</button>
                <a href="manage_patients.php" class="btn-cancel">Cancel</a>
            </form>
        </div>
        <?php endif; ?>
        
        <!-- Patients Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Total Appointments</th>
                    <th>Registered On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Reset pointer to beginning
                mysqli_data_seek($patients, 0);
                while($row = mysqli_fetch_assoc($patients)): 
                    $appointment_count = isset($appointment_counts[$row['id']]) ? $appointment_counts[$row['id']] : 0;
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                    <td>
                        <a href="manage_appointments.php?patient_id=<?php echo $row['id']; ?>" style="color: #17a2b8;">
                            <?php echo $appointment_count; ?> appointments
                        </a>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['id']; ?>" class="btn-edit">✏️ Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this patient? All their appointments will also be deleted!')">🗑️ Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                
                <?php if(mysqli_num_rows($patients) == 0): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No patients found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>