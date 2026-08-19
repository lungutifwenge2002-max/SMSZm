<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php"); exit();
}
$result = $conn->query("SELECT * FROM users");
$count = $result->num_rows;
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<style>
body{font-family:Arial;background:#f0f4f8;margin:0;padding:0}
.topbar{background:linear-gradient(90deg,#1e3c72,#2a5298);color:white;padding:20px 30px;display:flex;justify-content:space-between;align-items:center}
.topbar h1{font-size:32px;margin:0}
.dropdown{position:relative;display:inline-block}
.dropbtn{background:white;color:#1e3c72;padding:14px 24px;font-size:18px;font-weight:bold;border:none;border-radius:8px;cursor:pointer}
.dropdown-content{display:none;position:absolute;right:0;background:white;min-width:220px;box-shadow:0 8px 16px rgba(0,0,0,0.2);border-radius:8px;z-index:1;overflow:hidden}
.dropdown-content a{color:#333;padding:16px 20px;text-decoration:none;display:block;font-size:18px}
.dropdown-content a:hover{background:#eef3ff}
.dropdown:hover .dropdown-content{display:block}
.dropdown:hover .dropbtn{background:#e0e0e0}
.container{padding:20px}
.stats{background:white;padding:20px;border-radius:15px;margin:20px 0;font-size:22px;font-weight:bold;box-shadow:0 4px 10px rgba(0,0,0,0.1)}
table{width:100%;background:white;border-collapse:collapse;border-radius:15px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,0.1);margin-top:20px}
th{background:#1e3c72;color:white;padding:18px;font-size:20px}
td{padding:16px;font-size:18px;border-bottom:1px solid #ddd}
tr:hover{background:#eef3ff}
.role{padding:6px 12px;border-radius:20px;color:white;font-weight:bold}
.admin{background:#e74c3c}.teacher{background:#27ae60}.student{background:#3498db}.parent{background:#f39c12}
.links a{background:#2a5298;color:white;padding:12px 20px;text-decoration:none;border-radius:8px;margin-right:10px;font-size:18px}

.op-box{opacity:0; transition:0.3s}
tr:hover .op-box{opacity:1}</style>
</head>
<body>

<div class="topbar">
<h1>Welcome Admin <?php echo $_SESSION['name']; ?> 🎓</h1>

<div class="dropdown">
<button class="dropbtn">☰ Dashboards ▼</button>
<div class="dropdown-content">
<a href="teacher_dashboard.php?admin_view=1">👨‍🏫 Teacher Dashboard</a>
<a href="student_dashboard.php?admin_view=1">👨‍🎓 Student Dashboard</a>
<a href="parent_dashboard.php?admin_view=1">👪 Parent Dashboard</a>
</div>
</div>

</div>

<div class="container">
<div class="stats">📊 my_std Database: <?php echo $count; ?> users | Connected ✓</div>
<div class="links" style="margin-bottom:15px">
<a href="register.php" style="background:#27ae60">+ Register New Pupil</a>
<a href="logout.php">Logout</a> 
<a href="view_db.php">View Database</a>
</div>

<table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Operation</th></tr>
<?php while($row=$result->fetch_assoc()){
    $rc=$row['role'];
echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['email']}</td><td><span class='role $rc'>{$row['role']}</span></td><td><div class='op-box'><a href='edit.php?id={$row['id']}' style='background:#3498db;color:white;padding:6px 10px;border-radius:5px;text-decoration:none;margin-right:5px'>✏️ Edit</a><a href='admin_dashboard.php?delete={$row['id']}' onclick=\"return confirm('Delete?')\" style='background:#e74c3c;color:white;padding:6px 10px;border-radius:5px;text-decoration:none'>🗑️ Delete</a></div></td></tr>";} ?>
</table>
</div>

</body>
</html>