<?php
session_start();
include 'db_connect.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!='parent'){ header("Location: login.php"); exit(); }

$id = intval($_GET['id'] ?? 0);
$stu = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
$perf = $conn->query("SELECT * FROM performance WHERE student_id=$id");
$att = $conn->query("SELECT * FROM attendance WHERE student_id=$id ORDER BY att_date DESC");
$total = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE student_id=$id")->fetch_assoc()['c'];
$present = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE student_id=$id AND status='Present'")->fetch_assoc()['c'];
$per = $total>0 ? round($present/$total*100) : 0;
?>
<!DOCTYPE html>
<html><head><title>Parent View - <?=$stu['name']?></title>
<style>
body{font-family:Arial;background:#f1f5f9;padding:20px}
.card{background:white;padding:20px;border-radius:12px;margin-bottom:20px;box-shadow:0 2px 6px rgba(0,0,0,.1)}
table{width:100%;border-collapse:collapse} th{background:#1e3a8a;color:white;padding:12px} td{padding:10px;border-bottom:1px solid #eee}
.badge{padding:4px 10px;border-radius:12px;color:white;font-size:12px} .A{background:#16a34a} .B{background:#2563eb} .C{background:#f59e0b} .D{background:#ef4444} .F{background:#7f1d1d}
.present{background:#16a34a} .absent{background:#ef4444}
.btn{padding:10px 16px;background:#334155;color:white;border:none;border-radius:6px;text-decoration:none}
</style></head><body>
<a href="parent_dashboard.php" class="btn">← Back to Dashboard</a>
<h2>👨‍👧 Parent: <?=$_SESSION['name']?> — Viewing: <?=$stu['name']?></h2>

<div class="card">
<h3>📊 Attendance: <?=$present?>/<?=$total?> (<?=$per?>%)</h3>
<div style="background:#e2e8f0;height:18px;border-radius:10px;overflow:hidden"><div style="width:<?=$per?>%;background:#16a34a;height:100%"></div></div>
<br>
<table><tr><th>Date</th><th>Status</th></tr>
<?php while($a=$att->fetch_assoc()){ 
 $cls = $a['status']=='Present'?'present':'absent';
 echo "<tr><td>{$a['att_date']}</td><td><span class='badge $cls'>{$a['status']}</span></td></tr>";
}?>
</table>
</div>

<div class="card">
<h3>📚 Performance - 8 Subjects</h3>
<table><tr><th>Subject</th><th>Marks</th><th>Grade</th></tr>
<?php while($p=$perf->fetch_assoc()){
 echo "<tr><td>{$p['subject']}</td><td><b>{$p['marks']}</b></td><td><span class='badge {$p['grade']}'>{$p['grade']}</span></td></tr>";
}?>
</table>
</div>

</body></html>