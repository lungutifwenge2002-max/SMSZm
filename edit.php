<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: index.php"); exit(); }

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $conn->query("UPDATE users SET name='$name', email='$email', role='$role' WHERE id=$id");
    header("Location: admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html><head><title>Edit User</title>
<style>
body{font-family:Arial;background:#f0f4f8;display:flex;justify-content:center;align-items:center;height:100vh;margin:0}
.box{background:white;padding:40px;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.2);width:450px}
h1{text-align:center;color:#1e3c72}
input,select{width:100%;padding:14px;margin:10px 0;border:2px solid #ddd;border-radius:10px;font-size:18px;box-sizing:border-box}
button{width:100%;background:#27ae60;color:white;padding:14px;border:none;border-radius:10px;font-size:20px;font-weight:bold;cursor:pointer}
a{color:#1e3c72;text-decoration:none;font-weight:bold}
</style>
</head><body>
<div class="box">
<h1>✏️ Edit User #<?php echo $id; ?></h1>
<form method="POST">
<input type="text" name="name" value="<?php echo $user['name']; ?>" required>
<input type="email" name="email" value="<?php echo $user['email']; ?>" required>
<select name="role" required>
<option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>admin</option>
<option value="teacher" <?php if($user['role']=='teacher') echo 'selected'; ?>>teacher</option>
<option value="student" <?php if($user['role']=='student') echo 'selected'; ?>>student</option>
<option value="parent" <?php if($user['role']=='parent') echo 'selected'; ?>>parent</option>
</select>
<button type="submit" name="update">Update</button>
</form><br><a href="admin_dashboard.php">← Back</a>
</div></body></html>