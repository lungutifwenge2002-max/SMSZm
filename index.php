<?php
session_start();
include 'db_connect.php';

$error = "";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Allow login with email OR name (username)
    $sql = "SELECT * FROM users WHERE (email='$username' OR name='$username') AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == 'admin') header("Location: admin_dashboard.php");
        elseif ($row['role'] == 'teacher') header("Location: teacher_dashboard.php");
        elseif ($row['role'] == 'student') header("Location: student_dashboard.php");
        elseif ($row['role'] == 'parent') header("Location: parent_dashboard.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>School Login</title>
<style>
body{font-family:Arial;background:linear-gradient(135deg,#1e3c72,#2a5298);height:100vh;display:flex;justify-content:center;align-items:center;margin:0}
.login-box{background:white;padding:50px 40px;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.3);width:400px;text-align:center}
.login-box h1{font-size:36px;color:#1e3c72;margin-bottom:10px}
.login-box p{color:#666;font-size:14px;margin-bottom:25px}
.login-box input{width:100%;padding:16px;margin:12px 0;border:2px solid #ddd;border-radius:10px;font-size:18px;box-sizing:border-box}
.login-box input:focus{border-color:#2a5298;outline:none}
.login-btn{width:100%;background:linear-gradient(90deg,#1e3c72,#2a5298);color:white;padding:16px;border:none;border-radius:10px;font-size:22px;font-weight:bold;cursor:pointer;margin-top:15px}
.login-btn:hover{opacity:0.9;transform:scale(1.02)}
.error{background:#ffdddd;color:#d8000c;padding:12px;border-radius:8px;margin-bottom:15px;font-size:16px}
.demo{text-align:left;background:#f8f9ff;padding:15px;border-radius:10px;margin-top:20px;font-size:13px;line-height:1.6}
</style>
</head>
<body>

<div class="login-box">
<h1>School Login 🎓</h1>
<p>DB: my_std Connected ✓</p>

<?php if($error) echo "<div class='error'>$error</div>"; ?>

<form method="POST">
<input type="text" name="username" placeholder="👤 Username / Email" required value="admin@school.com">
<input type="password" name="password" placeholder="🔒 Password" required value="admin123">
<button type="submit" name="login" class="login-btn">Login</button>
</form>


</div>

</body>
</html>