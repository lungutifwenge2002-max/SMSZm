<?php
session_start();
$allowed = ['admin','parent'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed)) { header("Location: index.php"); exit(); }
include 'db_connect.php';
$result = $conn->query("SELECT * FROM users WHERE role='student' ORDER BY name");
?>
<!DOCTYPE html>
<html><head><title>Parent Dashboard</title>
<style>
body{font-family:Arial;background:#f0f4f8;margin:0;padding:0}
.topbar{background:#f39c12;color:white;padding:20px 30px;display:flex;justify-content:space-between;align-items:center}
.topbar h1{font-size:28px;margin:0}
.dropdown{position:relative;display:inline-block}
.dropbtn{background:white;color:#f39c12;padding:12px 20px;font-size:16px;font-weight:bold;border:none;border-radius:8px;cursor:pointer}
.dropdown-content{display:none;position:absolute;right:0;background:white;min-width:240px;box-shadow:0 8px 16px rgba(0,0,0,0.2);border-radius:8px;overflow:hidden;z-index:1}
.dropdown-content a{color:#333;padding:14px 18px;text-decoration:none;display:block;font-size:16px}
.dropdown-content a:hover{background:#fef9e7}
.dropdown:hover .dropdown-content{display:block}
.container{padding:20px}
table{width:100%;background:white;border-collapse:collapse;border-radius:15px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,0.1);margin-top:20px}
th{background:#f39c12;color:white;padding:18px;font-size:18px} td{padding:14px;font-size:16px;border-bottom:1px solid #ddd}
a.btn{background:#1e3c72;color:white;padding:10px 16px;text-decoration:none;border-radius:8px;margin-right:5px}
</style>
</head><body>
<div class="topbar"><h1>👪 Parent: <?php echo $_SESSION['name']; ?></h1>
<div class="dropdown"><button class="dropbtn">☰ My Children ▼</button><div class="dropdown-content">
<a href="attendance.php">📅 Children's Attendance</a>
<a href="performance.php">📊 Children's Performance</a>
<a href="parent_dashboard.php">👥 View Children</a>
</div></div></div>
<div class="container"><a href="admin_dashboard.php" class="btn" <?php if($_SESSION['role']!='admin') echo 'style="display:none"'; ?>>← Admin</a> <a href="logout.php" class="btn">Logout</a>
<table><tr><th>ID</th><th>Child Name</th><th>Email</th><th>Class</th><th>Operation</th></tr>
<?php while($row=$result->fetch_assoc()){ $sid=$row['id']; $class=$row['class']??'Grade 12'; echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['email']}</td><td>$class</td><td><a href='parent_view_student.php?id=$sid' style='background:#1e3a8a;color:white;padding:8px 12px;border-radius:6px;text-decoration:none'>👁️ View Marks & Attendance</a></td></tr>"; } ?>
</table></div></body></html>