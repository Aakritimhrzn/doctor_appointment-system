<?php
require_once '../config/db.php';
requireAdminLogin();

// Handle Add/Edit/Delete
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_doctor'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $department_id = intval($_POST['department_id']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $photo_url = mysqli_real_escape_string($conn, $_POST['photo_url']);
    $available_days = mysqli_real_escape_string($conn, $_POST['available_days']);
    $available_time = mysqli_real_escape_string($conn, $_POST['available_time']);
    
    $query = "INSERT INTO doctors (name, department_id, qualification, photo_url, available_days, available_time) 
              VALUES ('$name', $department_id, '$qualification', '$photo_url', '$available_days', '$available_time')";
    mysqli_query($conn, $query);
    header("Location: manage_doctors.php");
    exit();
}

if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM doctors WHERE id=$id");
    header("Location: manage_doctors.php");
    exit();
}

if(isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_doctor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM doctors WHERE id=$edit_id"));
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_doctor'])) {
    $id = intval($_POST['doctor_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $department_id = intval($_POST['department_id']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $photo_url = mysqli_real_escape_string($conn, $_POST['photo_url']);
    $available_days = mysqli_real_escape_string($conn, $_POST['available_days']);
    $available_time = mysqli_real_escape_string($conn, $_POST['available_time']);
    
    $query = "UPDATE doctors SET name='$name', department_id=$department_id, qualification='$qualification', 
              photo_url='$photo_url', available_days='$available_days', available_time='$available_time' WHERE id=$id";
    mysqli_query($conn, $query);
    header("Location: manage_doctors.php");
    exit();
}

$doctors = mysqli_query($conn, "SELECT d.*, dept.name as dept_name FROM doctors d JOIN departments dept ON d.department_id = dept.id");
$departments = mysqli_query($conn, "SELECT * FROM departments");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-nav { background: #1e3c72; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .admin-nav a { color: white; text-decoration: none; margin-left: 1rem; }
        .admin-container { padding: 2rem; max-width: 1400px; margin: 0 auto; }
        .form-section, .list-section { background: white; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; }
        table { width: 100%; background: white; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #1e3c72; color: white; }
        .btn-edit { background: #ffc107; color: #333; padding: 3px 8px; text-decoration: none; border-radius: 3px; }
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
        <h1>Manage Doctors</h1>
        
        <div class="form-section">
            <h2><?php echo isset($edit_doctor) ? 'Edit Doctor' : 'Add New Doctor'; ?></h2>
            <form method="POST">
                <?php if(isset($edit_doctor)): ?>
                    <input type="hidden" name="doctor_id" value="<?php echo $edit_doctor['id']; ?>">
                <?php endif; ?>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Doctor Name</label>
                        <input type="text" name="name" required value="<?php echo isset($edit_doctor) ? $edit_doctor['name'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department_id" required>
                            <option value="">Select Department</option>
                            <?php mysqli_data_seek($departments, 0); while($dept = mysqli_fetch_assoc($departments)): ?>
                                <option value="<?php echo $dept['id']; ?>" <?php echo (isset($edit_doctor) && $edit_doctor['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                                    <?php echo $dept['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Qualification</label>
                        <input type="text" name="qualification" required value="<?php echo isset($edit_doctor) ? $edit_doctor['qualification'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Photo URL</label>
                        <input type="text" name="photo_url" value="<?php echo isset($edit_doctor) ? $edit_doctor['photo_url'] : 'https://randomuser.me/api/portraits/men/1.jpg'; ?>">
                    </div>
                    <div class="form-group">
                        <label>Available Days (e.g., Mon, Wed, Fri)</label>
                        <input type="text" name="available_days" required value="<?php echo isset($edit_doctor) ? $edit_doctor['available_days'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Available Time (e.g., 10:00 AM - 4:00 PM)</label>
                        <input type="text" name="available_time" required value="<?php echo isset($edit_doctor) ? $edit_doctor['available_time'] : ''; ?>">
                    </div>
                </div>
                <button type="submit" name="<?php echo isset($edit_doctor) ? 'update_doctor' : 'add_doctor'; ?>" class="btn-submit" style="width: auto; margin-top: 1rem;">
                    <?php echo isset($edit_doctor) ? 'Update Doctor' : 'Add Doctor'; ?>
                </button>
                <?php if(isset($edit_doctor)): ?>
                    <a href="manage_doctors.php" style="margin-left: 1rem;">Cancel Edit</a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="list-section">
            <h2>Doctor List</h2>
            <table>
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Department</th><th>Qualification</th><th>Days</th><th>Time</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php mysqli_data_seek($doctors, 0); while($row = mysqli_fetch_assoc($doctors)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['dept_name']; ?></td>
                        <td><?php echo $row['qualification']; ?></td>
                        <td><?php echo $row['available_days']; ?></td>
                        <td><?php echo $row['available_time']; ?></td>
                        <td>
                            <a href="?edit=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this doctor?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>