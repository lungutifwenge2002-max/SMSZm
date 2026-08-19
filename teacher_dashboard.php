<?php
session_start();
include 'db_connect.php';
if(!isset($_SESSION['role']) || ($_SESSION['role']!='teacher' && $_SESSION['role']!='admin')){ header("Location: index.php"); exit(); }

// Handle delete marks/attendance
if(isset($_GET['del_mark'])){ $id=$_GET['del_mark']; $conn->query("DELETE FROM performance WHERE id=$id"); header("Location: teacher_dashboard.php"); exit(); }
if(isset($_GET['del_att'])){ $id=$_GET['del_att']; $conn->query("DELETE FROM attendance WHERE id=$id"); header("Location: teacher_dashboard.php"); exit(); }

$students = $conn->query("SELECT * FROM users WHERE role='student'");
?>
<!DOCTYPE html><html><head><title>Teacher Dashboard</title>
<style>
body{font-family:Arial;background:#f0f4f8;margin:0}
.topbar{background:#27ae60;color:white;padding:20px 30px;display:flex;justify-content:space-between;align-items:center}
.topbar h1{margin:0}
.container{padding:20px}
table{width:100%;background:white;border-collapse:collapse;border-radius:12px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,0.1)}
th{background:#27ae60;color:white;padding:14px} td{padding:12px;border-bottom:1px solid #eee}
tr:hover{background:#e8f5e9}
.op-box{opacity:0;transition:0.3s}
tr:hover .op-box{opacity:1}
.btn{padding:6px 10px;border-radius:5px;text-decoration:none;color:white;font-size:12px;margin-right:4px;font-weight:bold}
.blue{background:#3498db}.orange{background:#f39c12}.red{background:#e74c3c}
.role{background:#3498db;color:white;padding:5px 10px;border-radius:20px;font-size:12px;font-weight:bold}
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:100;justify-content:center;align-items:center}
.modal-box{background:white;padding:25px;border-radius:15px;width:500px;max-height:90vh;overflow:auto}
.input-field{width:100%;padding:10px;margin:6px 0;border:1px solid #ddd;border-radius:6px;box-sizing:border-box}
</style>
</head><body>
<div class="topbar"><h1>Teacher: <?php echo $_SESSION['name']; ?></h1><a href="logout.php" style="background:white;color:#27ae60;padding:8px 14px;border-radius:6px;text-decoration:none;font-weight:bold">Logout</a></div>
<div class="container">
<h3>👨‍🎓 My Students — Hover to Manage Marks & Attendance</h3>
<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Operation</th></tr>
<?php while($row=$students->fetch_assoc()){ 
 $sid=$row['id'];
?>
<tr><td><?php echo $sid; ?></td><td><?php echo $row['name']; ?></td><td><?php echo $row['email']; ?></td><td><span class="role">student</span></td>
<td><div class="op-box">
<td>
  <a href="manage_student.php?id=<?=$row['id']?>#marks" style="text-decoration:none">
    <button style="background:#0ea5e9;color:white;padding:5px 10px;border:none;border-radius:5px;cursor:pointer">✏️ Marks</button>
  </a>
  <a href="manage_student.php?id=<?=$row['id']?>#attendance" style="text-decoration:none">
    <button style="background:#f97316;color:white;padding:5px 10px;border:none;border-radius:5px;cursor:pointer">📅 Attendance</button>
  </a>
</td>
</div></td></tr>
<?php } ?>
</table>
</div>
</body></html>