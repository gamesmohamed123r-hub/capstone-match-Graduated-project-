<?php
session_start();
include("db.php");

/**
 * 1. معالجة الحذف أولاً
 * بننفذ المسح قبل تشغيل المحرك عشان المحرك ميسكنش الناس اللي بنمسحهم تاني في نفس اللحظة
 */
if (isset($_GET['delete'])) {
    $gid = $_GET['delete'];
    try {
        $connection->beginTransaction();

        // أ- فك حجز المشروع في جدول المشاريع وجعله متاحاً (is_assigned = 0)
        $upProj = $connection->prepare("UPDATE projects SET is_assigned = 0, assigned_group_id = NULL WHERE assigned_group_id = ?");
        $upProj->execute([$gid]);

        // ب- مسح اختيارات الطلاب دي نهائياً من الداتابيز عشان ميتسكنوش تاني أوتوماتيك
        $delChoices = $connection->prepare("DELETE FROM student_choices WHERE group_id = ?");
        $delChoices->execute([$gid]);

        // ج- حذف سجل التوصية من جدول الـ recommendations
        $delRec = $connection->prepare("DELETE FROM recommendations WHERE group_id = ?");
        $delRec->execute([$gid]);

        $connection->commit();
        
        // إعادة توجيه لضمان تحديث الصفحة ونظافة الرابط
        header("Location: admin_dashboard.php?status=success");
        exit();
    } catch (Exception $e) {
        $connection->rollBack();
        // يمكنك تفعيل السطر التالي في حالة البرمجة فقط لرؤية الأخطاء
        // echo "Error: " . $e->getMessage();
    }
}

/**
 * 2. تشغيل المحرك التلقائي
 * بيشتغل بعد المسح عشان يسكن أي طلاب جدد أو متبقيين
 */
include("auto_match_engine.php"); 

/**
 * 3. جلب المجموعات المسكنة لعرضها في الكروت
 */
$query = "SELECT r.*, p.project_name 
          FROM recommendations r 
          JOIN projects p ON r.project_id = p.project_id 
          ORDER BY r.id DESC";
$results = $connection->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الإدارة - Project Recommender</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/animate.min.css">
    <style>
        body { background-color: #F1F5FF; font-family: 'Segoe UI', sans-serif; }
        .navbar { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 15px 30px; }
        .nav-logo { width: 120px; }
        .dashboard-header { margin: 40px 0; text-align: center; }
        .dashboard-header h2 { font-weight: 800; color: #333; }
        
        .card-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; padding: 20px; }
        .project-card { 
            background: white; border-radius: 20px; padding: 25px; 
            border-top: 5px solid #E63131; transition: 0.3s; 
            position: relative; box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .project-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        
        .group-id { color: #E63131; font-weight: bold; font-size: 14px; margin-bottom: 10px; display: block; }
        .project-title { font-size: 20px; font-weight: 700; color: #1a1a1a; margin-bottom: 15px; height: 55px; overflow: hidden; }
        
        .match-badge { 
            background: #FFF0F0; color: #E63131; padding: 5px 15px; 
            border-radius: 50px; font-weight: bold; font-size: 13px; 
        }
        
        .card-footer { 
            margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; 
            display: flex; justify-content: space-between; align-items: center; 
        }
        .btn-details { color: #555; text-decoration: none; font-weight: 600; font-size: 14px; }
        .btn-delete { color: #dc3545; text-decoration: none; font-size: 13px; cursor: pointer; }
        .btn-delete:hover { text-decoration: underline; }

        .empty-state { text-align: center; padding: 100px; color: #888; width: 100%; grid-column: 1 / -1; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <img src="imgs/Screenshot 2025-10-10 143923.png" class="nav-logo" alt="Logo">
        <div class="ms-auto d-flex align-items-center">
            <span class="me-3 fw-bold">أهلاً دكتور / Admin</span>
            <a href="logout.php" class="btn btn-outline-danger rounded-pill px-4">خروج</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="dashboard-header animate__animated animate__fadeInDown">
        <h2>المجموعات التي تم تشكيلها</h2>
        <p class="text-muted">يتم تحديث المجموعات تلقائياً بناءً على توافق مهارات الطلاب</p>
    </div>

    <div class="card-container">
    <?php if (count($results) > 0): ?>
        <?php 
        $count = 1; 
        foreach ($results as $row): 
            $current_num = $count++; // حفظ رقم المجموعة الحالي
        ?>
            <div class="project-card animate__animated animate__fadeInUp">
                <span class="group-id">
                    <i class="fas fa-layer-group"></i> مجموعة رقم <?php echo $current_num; ?>
                </span>
                
                <h3 class="project-title">
                    <i class="fas fa-project-diagram" style="color: #E63131; margin-left: 8px;"></i>
                    <?php echo htmlspecialchars($row['project_name']); ?>
                </h3>
                
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-muted small">نسبة التوافق:</span>
                    <span class="match-badge">
                        <i class="fas fa-percentage"></i> <?php echo $row['match_score']; ?>%
                    </span>
                </div>

                <div class="card-footer">
                    <a href="group_details.php?id=<?php echo $row['group_id']; ?>&num=<?php echo $current_num; ?>" class="btn-details">
                        <i class="fas fa-users"></i> تفاصيل الفريق
                    </a>
                    <a href="admin_dashboard.php?delete=<?php echo $row['group_id']; ?>" 
                       class="btn-delete" 
                       onclick="return confirm('هل أنت متأكد من مسح هذه المجموعة وطلابها نهائياً؟')">
                       <i class="fas fa-trash-alt"></i> مسح
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state animate__animated animate__fadeIn">
            <i class="fas fa-folder-open fa-3x mb-3"></i>
            <p>لا توجد مجموعات مسكنة حالياً.</p>
        </div>
    <?php endif; ?>
    </div>
</div>

<script src="javascript/bootstrap.bundle.min.js"></script>
</body>
</html>