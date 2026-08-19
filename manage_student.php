<?php
session_start();
include 'db_connect.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!='teacher'){ header("Location: login.php"); exit(); }

$student_id = intval($_GET['id'] ?? 0);
if($student_id==0) die("No student ID");

// Get student
$stu = $conn->query("SELECT * FROM users WHERE id=$student_id")->fetch_assoc();

// AUTO-CREATE 8 subjects if new student has none
$check = $conn->query("SELECT COUNT(*) as c FROM performance WHERE student_id=$student_id")->fetch_assoc()['c'];
if($check==0){
  $conn->query("INSERT INTO performance (student_id, subject, marks, grade) VALUES
  ($student_id,'Mathematics',0,'F'), ($student_id,'English',0,'F'), ($student_id,'Biology',0,'F'),
  ($student_id,'Physics',0,'F'), ($student_id,'Chemistry',0,'F'), ($student_id,'Computer Studies',0,'F'),
  ($student_id,'Geography',0,'F'), ($student_id,'Civic Education',0,'F')");
}

// UPDATE MARKS
if(isset($_POST['update_marks']) && isset($_POST['marks'])){
  foreach($_POST['marks'] as $perf_id => $mark){
    $mark = intval($mark);
    $grade = $mark>=80?'A':($mark>=70?'B':($mark>=60?'C':($mark>=50?'D':'F')));
    $conn->query("UPDATE performance SET marks=$mark, grade='$grade' WHERE id=$perf_id AND student_id=$student_id");
  }
  $msg="✅ Marks Updated! Student & Parent can now see new results.";
}
// UPDATE ATTENDANCE
if(isset($_POST['update_att']) && isset($_POST['status'])){
  foreach($_POST['status'] as $att_id => $status){
    $status = $conn->real_escape_string($status);
    $conn->query("UPDATE attendance SET status='$status' WHERE id=$att_id AND student_id=$student_id");
  }
  $msg="✅ Attendance Updated!";
}
if(isset($_POST['add_att'])){
  $date = $_POST['att_date']; $status = $_POST['new_status'];
  $conn->query("INSERT INTO attendance (student_id, att_date, status) VALUES ($student_id,'$date','$status')");
  $msg="✅ New attendance added!";
}

$perf_q = $conn->query("SELECT * FROM performance WHERE student_id=$student_id");
$att_q = $conn->query("SELECT * FROM attendance WHERE student_id=$student_id ORDER BY att_date DESC");
?>
<!DOCTYPE html>
<html><head><title>Manage <?=$stu['name']?></title>
<style>
body{font-family:Arial;padding:20px;background:#f4f6f9}
.card{background:white;padding:20px;border-radius:10px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,.1)}
input,select{padding:8px;border:1px solid #ccc;border-radius:5px}
.btn{padding:10px 18px;border:none;border-radius:5px;color:white;cursor:pointer}
.btn-green{background:#16a34a} .btn-blue{background:#2563eb} .btn-back{background:#64748b}
table{width:100%;border-collapse:collapse} th,td{padding:10px;border-bottom:1px solid #eee;text-align:left}
</style></head><body>
<a href="teacher_dashboard.php"><button class="btn btn-back">← Back to Students</button></a>
<h2>🎓 Managing: <?=$stu['name']?> (<?=$stu['email']?>)</h2>
<?php if(isset($msg)) echo "<p style='background:#dcfce7;padding:10px;border-radius:5px;color:green'>$msg</p>"; ?>
<div class="card">
<h3>📚 8 Subjects Performance — Edit & Save</h3>
<form method="POST">
<table><tr><th>Subject</th><th>Marks (0-100)</th><th>Grade</th></tr>
<?php while($r=$perf_q->fetch_assoc()){
 echo "<tr><td>{$r['subject']}</td><td><input type='number' name='marks[{$r['id']}]' value='{$r['marks']}' min=0 max=100 style='width:80px'></td><td>{$r['grade']}</td></tr>";
}?>
</table><br>
<button name="update_marks" class="btn btn-green">💾 Save Marks — Parents Will See</button>
</form>
</div>
<div class="card">
<h3>🗓️ Attendance — Edit Status</h3>
<form method="POST">
<table><tr><th>Date</th><th>Status</th></tr>
<?php while($a=$att_q->fetch_assoc()){
  echo "<tr><td>{$a['att_date']}</td><td><select name='status[{$a['id']}]'><option ".($a['status']=='Present'?'selected':'').">Present</option><option ".($a['status']=='Absent'?'selected':'').">Absent</option></select></td></tr>";
}?>
</table><br>
<button name="update_att" class="btn btn-blue">💾 Save Attendance</button>
</form>
<br><hr>
<h4>Add New Attendance Day</h4>
<form method="POST">
<input type="date" name="att_date" required>
<select name="new_status"><option>Present</option><option>Absent</option></select>
<button name="add_att" class="btn btn-green">+ Add</button>
</form>
</div>
</body></html>