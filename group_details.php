<?php
include("db.php");

// استلام الرقم الترتيبي من الرابط
$group_number = $_GET['num'] ?? '1'; 
$gid = $_GET['id'] ?? '';

if ($gid) {
    // جلب أسماء الطلاب
    $stmt = $connection->prepare("SELECT DISTINCT student_name FROM student_choices WHERE group_id = ?");
    $stmt->execute([$gid]);
    $students = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تفاصيل المجموعة</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .details-card { max-width: 600px; margin: 50px auto; border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .card-header { background: #E63131; color: white; border-radius: 15px 15px 0 0 !important; text-align: center; padding: 20px; }
        .student-item { padding: 15px; border-bottom: 1px solid #eee; display: flex; align-items: center; }
        .student-item:last-child { border-bottom: none; }
        .avatar { width: 40px; height: 40px; background: #FFF0F0; border-radius: 50%; margin-left: 15px; display: flex; align-items: center; justify-content: center; color: #E63131; border: 1px solid #FFDADA; }
    </style>
</head>
<body>

<div class="container">
    <div class="card details-card">
        <div class="card-header">
            <h3 class="mb-0"><i class="fas fa-users-viewfinder me-2"></i> تفاصيل المجموعة رقم <?php echo htmlspecialchars($group_number); ?></h3>
        </div>

        <div class="card-body p-0">
            <?php if ($students): ?>
                <?php foreach ($students as $s): ?>
                    <div class="student-item animate__animated animate__fadeIn">
                        <div class="avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="fw-bold" style="color: #333; font-size: 1.1rem;">
                            <?php echo htmlspecialchars($s['student_name']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center p-5">
                    <i class="fas fa-user-slash fa-3x mb-3" style="color: #ccc;"></i>
                    <p class="text-muted">لا يوجد طلاب في هذه المجموعة.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="card-footer text-center bg-white border-0 pb-4">
            <a href="admin_dashboard.php" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-right me-2"></i> رجوع للوحة التحكم
            </a>
        </div>
    </div>
</div>

</body>
</html>