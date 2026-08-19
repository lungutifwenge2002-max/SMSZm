<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['teacher','parent','admin'])) {
    header("Location: index.php"); exit();
}
include 'db_connect.php';
$students = $conn->query("SELECT * FROM users WHERE role='student'");
?>
<!DOCTYPE html>
<html><head><title>Performance</title>
<style>
body{font-family:Arial;background:#f0f4f8;padding:20px}
.header{background:#8e44ad;color:white;padding:20px;border-radius:15px;text-align:center}
table{width:100%;background:white;border-collapse:collapse;border-radius:15px;overflow:hidden;margin-top:20px;box-shadow:0 4px 10px rgba(0,0,0,0.1)}
th{background:#8e44ad;color:white;padding:15px} td{padding:14px;border-bottom:1px solid #ddd;text-align:center}
.grade-A{background:#27ae60;color:white;padding:5px 12px;border-radius:20px} .grade-B{background:#3498db;color:white;padding:5px 12px;border-radius:20px} .grade-C{background:#f39c12;color:white;padding:5px 12px;border-radius:20px}
a.btn{background:#1e3c72;color:white;padding:10px 18px;text-decoration:none;border-radius:8px}
</style>
</head><body>
<div class="header"><h1>📊 Performance Report - <?php echo $_SESSION['name']; ?></h1></div><br>
<a href="<?php echo $_SESSION['role']; ?>_dashboard.php" class="btn">← Back to Dashboard</a>
<table><tr><th>Student Name</th><th>Maths</th><th>English</th><th>Science</th><th>Average</th><th>Grade</th></tr>
<?php while($s=$students->fetch_assoc()){
 $m=rand(50,95); $e=rand(50,95); $sc=rand(50,95); $avg=round(($m+$e+$sc)/3);
 $grade = $avg>=75 ? 'A' : ($avg>=60 ? 'B' : 'C');
 echo "<tr><td>{$s['name']}</td><td>$m%</td><td>$e%</td><td>$sc%</td><td><b>$avg%</b></td><td><span class='grade-$grade'>Grade $grade</span></td></tr>";
} ?>
</table></body></html>