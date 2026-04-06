<?php
require_once '../config/db.php';
requireAdminLogin();

// Add Department
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_department'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    mysqli_query($conn, "INSERT INTO departments (name, description) VALUES ('$name', '$description')");
    header("Location: manage_departments.php");
    exit();
}

// Delete Department
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM departments WHERE id=$id");
    header("Location: manage_departments.php");
    exit();
}

$departments = mysqli_query($conn, "SELECT * FROM departments");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-nav { background: #1e3c72; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .admin-nav a { color: white; text-decoration: none; margin-left: 1rem; }
        .admin-container { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .form-section, .list-section { background: white; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem; }
        table { width: 100%; background: white; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #1e3c72; color: white; }
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
        <h1>Manage Departments</h1>
        
        <div class="form-section">
            <h2>Add New Department</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Department Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" required></textarea>
                </div>
                <button type="submit" name="add_department" class="btn-submit" style="width: auto;">Add Department</button>
            </form>
        </div>
        
        <div class="list-section">
            <h2>Department List</h2>
            <table>
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Description</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($departments)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this department? This will also delete related doctors!')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>