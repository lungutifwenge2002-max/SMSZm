<?php
session_start();
$allowed = ['admin','student'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed)) { header("Location: index.php"); exit(); }
include 'db_connect.php';

// AUTO CREATE TABLES if missing - no more warnings!
$conn->query("CREATE TABLE IF NOT EXISTS performance (id INT AUTO_INCREMENT PRIMARY KEY, student_id INT, subject VARCHAR(100), marks INT, grade VARCHAR(10))");
$conn->query("CREATE TABLE IF NOT EXISTS attendance (id INT AUTO_INCREMENT PRIMARY KEY, student_id INT, att_date DATE, status VARCHAR(20))");

$id = $_SESSION['id'];
// If admin is viewing, show admin's own record or default to student 5
if($_SESSION['role']=='admin' && isset($_GET['admin_view'])){ $id = intval($_GET['admin_view']); if($id==1) $id=5; }

$student = $conn->query("SELECT * FROM users WHERE id=$id");
$student = $student ? $student->fetch_assoc() : ['name'=>$_SESSION['name']];

$perf_q = $conn->query("SELECT * FROM performance WHERE student_id=$id");
$att_q = $conn->query("SELECT * FROM attendance WHERE student_id=$id ORDER BY att_date DESC");

$perf_rows = $perf_q ? $perf_q->fetch_all(MYSQLI_ASSOC) : [];
$att_rows = $att_q ? $att_q->fetch_all(MYSQLI_ASSOC) : [];

$total = count($att_rows);
$present = 0; foreach($att_rows as $a){ if($a['status']=='Present') $present++; }
$perc = $total > 0 ? round(($present/$total)*100) : 0;
?>
<!DOCTYPE html><html><head><title>Student Dashboard</title>
<style>
body{font-family:Arial;background:#f0f4f8;margin:0;padding:0}
.header{background:#3498db;color:white;padding:18px 25px;display:flex;justify-content:space-between;align-items:center}
.dropdown{position:relative;display:inline-block}
.dropbtn{background:white;color:#3498db;padding:10px 16px;font-weight:bold;border:none;border-radius:8px;cursor:pointer}
.dropdown-content{display:none;position:absolute;right:0;background:white;min-width:200px;box-shadow:0 8px 16px rgba(0,0,0,0.2);border-radius:8px;overflow:hidden;z-index:10}
.dropdown-content a{color:#333;padding:12px 16px;text-decoration:none;display:block;font-weight:bold}
.dropdown-content a:hover{background:#eef3ff}
.dropdown:hover .dropdown-content{display:block}
.container{padding:20px}
.card{background:white;padding:20px;border-radius:15px;margin-bottom:20px;box-shadow:0 4px 10px rgba(0,0,0,0.1)}
table{width:100%;background:white;border-collapse:collapse;border-radius:12px;overflow:hidden}
th{background:#3498db;color:white;padding:12px} td{padding:12px;border-bottom:1px solid #eee}
.badge{padding:5px 10px;border-radius:20px;color:white;font-weight:bold;font-size:12px}
.A{background:#27ae60}.B{background:#2ecc71}.C{background:#f39c12}.Present{background:#27ae60}.Absent{background:#e74c3c}.Late{background:#f39c12}
.tab{display:none} .tab.active{display:block}
.tab-btn{padding:10px 16px;border:none;border-radius:8px;margin-right:6px;cursor:pointer;font-weight:bold}
.tab-btn.active{background:#3498db;color:white} .tab-btn.inactive{background:#ddd}
</style></head><body>
<div class="header"><h1>🎓 <?php echo $student['name']; ?></h1>
<div class="dropdown"><button class="dropbtn">☰ My Menu ▼</button>
<div class="dropdown-content">
<a href="#" onclick="showTab('perf');return false;">📊 My Performance</a>
<a href="#" onclick="showTab('att');return false;">📅 My Attendance (<?php echo $perc; ?>%)</a>
<a href="logout.php">Logout</a>
<a href="admin_dashboard.php">← Back to Admin</a>
</div></div></div>
<div class="container">
<button id="btn-perf" class="tab-btn active" onclick="showTab('perf')">📊 Performance</button>
<button id="btn-att" class="tab-btn inactive" onclick="showTab('att')">📅 Attendance - <?php echo $perc; ?>%</button>

<div id="perf" class="tab active"><div class="card"><h3>📊 My Performance</h3>
<table><tr><th>Subject</th><th>Marks</th><th>Grade</th></tr>
<?php if(empty($perf_rows)) echo "<tr><td colspan=3 style='text-align:center;color:#999;padding:20px'>No marks yet</td></tr>";
else foreach($perf_rows as $r){ echo "<tr><td>{$r['subject']}</td><td><b>{$r['marks']}</b></td><td><span class='badge {$r['grade']}'>{$r['grade']}</span></td></tr>"; } ?>
</table></div></div>

<div id="att" class="tab"><div class="card"><h3>📅 My Attendance - <?php echo $present; ?>/<?php echo $total; ?> (<?php echo $perc; ?>%)</h3>
<table><tr><th>Date</th><th>Status</th></tr>
<?php if(empty($att_rows)) echo "<tr><td colspan=2 style='text-align:center;color:#999;padding:20px'>No attendance yet</td></tr>";
else foreach($att_rows as $r){ echo "<tr><td>{$r['att_date']}</td><td><span class='badge {$r['status']}'>{$r['status']}</span></td></tr>"; } ?>
</table></div></div>
</div>
<script>
function showTab(t){
 document.getElementById('perf').classList.remove('active');
 document.getElementById('att').classList.remove('active');
 document.getElementById('btn-perf').className='tab-btn inactive';
 document.getElementById('btn-att').className='tab-btn inactive';
 document.getElementById(t).classList.add('active');
 document.getElementById('btn-'+t).className='tab-btn active';
}
</script></body></html>