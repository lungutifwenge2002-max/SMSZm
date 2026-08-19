<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['teacher','parent','admin'])) {
    header("Location: index.php"); exit();
}
include 'db_connect.php';
$students = $conn->query("SELECT * FROM users WHERE role='student'");
?>
<!DOCTYPE html>
<html><head><title>Attendance</title>
<style>
body{font-family:Arial;background:#f0f4f8;padding:20px}
.header{background:#1e3c72;color:white;padding:20px;border-radius:15px;text-align:center}
table{width:100%;background:white;border-collapse:collapse;border-radius:15px;overflow:hidden;margin-top:20px;box-shadow:0 4px 10px rgba(0,0,0,0.1)}
th{background:#1e3c72;color:white;padding:15px} td{padding:14px;border-bottom:1px solid #ddd;text-align:center}
.present{background:#27ae60;color:white;padding:5px 10px;border-radius:20px} .absent{background:#e74c3c;color:white;padding:5px 10px;border-radius:20px}
a.btn{background:#1e3c72;color:white;padding:10px 18px;text-decoration:none;border-radius:8px}
</style>
</head><body>
<div class="header"><h1>📅 Attendance - <?php echo $_SESSION['role']; ?>: <?php echo $_SESSION['name']; ?></h1></div><br>
<a href="<?php echo $_SESSION['role']; ?>_dashboard.php" class="btn">← Back to Dashboard</a>
<table><tr><th>Student Name</th><th>Email</th><th>Date</th><th>Status</th></tr>
<?php while($s=$students->fetch_assoc()){
 $status = rand(0,1) ? 'Present' : 'Absent';
 $cls = rand(0,1) ? 'present' : 'absent';
 echo "<tr><td>{$s['name']}</td><td>{$s['email']}</td><td>".date('Y-m-d')."</td><td><span class='$cls'>$status</span></td></tr>";
} ?>
</table></body></html>