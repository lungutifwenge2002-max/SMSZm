<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php"); exit();
}

$msg = "";
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $msg = "<div style='background:#ffdddd;color:red;padding:12px;border-radius:8px'>Email already exists!</div>";
    } else {
        $conn->query("INSERT INTO users (name,email,password,role) VALUES ('$name','$email','$password','$role')");
        $msg = "<div style='background:#ddffdd;color:green;padding:12px;border-radius:8px'>✅ $role Registered Successfully!</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register Pupil</title>
<style>
body{font-family:Arial;background:#f0f4f8;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0}
.box{background:white;padding:40px;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.2);width:450px}
.box h1{text-align:center;color:#1e3c72;font-size:32px}
input,select{width:100%;padding:15px;margin:10px 0;border:2px solid #ddd;border-radius:10px;font-size:18px;box-sizing:border-box}
button{width:100%;background:linear-gradient(90deg,#1e3c72,#2a5298);color:white;padding:15px;border:none;border-radius:10px;font-size:20px;font-weight:bold;cursor:pointer;margin-top:10px}
a.back{display:inline-block;margin-top:15px;text-decoration:none;color:#2a5298;font-weight:bold}
</style>
</head>
<body>
<div class="box">
<h1>📝 Register New User</h1>
<?php echo $msg; ?>
<form method="POST">
<input type="text" name="name" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="text" name="password" placeholder="Password" required>
<select name="role" required>
<option value="">-- Select Role --</option>
<option value="student" selected>Student / Pupil</option>
<option value="teacher">Teacher</option>
<option value="parent">Parent</option>
<option value="admin">Admin</option>
</select>
<button type="submit" name="register">Register</button>
</form>
<a href="admin_dashboard.php" class="back">← Back to Dashboard</a>
</div>
</body>
</html>