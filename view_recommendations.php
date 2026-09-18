<?php
session_start();
include("db.php");
$group_id = $_GET['group_id'];

$stmt = $connection->prepare("SELECT r.match_score, p.project_name, p.category 
                                FROM recommendations r 
                                JOIN projects p ON r.project_id = p.project_id 
                                WHERE r.group_id = :gid 
                                ORDER BY r.match_score DESC");
$stmt->execute(['gid' => $group_id]);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نتائج التوصيات للمجموعة #<?php echo $group_id; ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/animate.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');
        body { background: #f8f9fa; font-family: 'Cairo', sans-serif; }
        .header-section { background: white; padding: 30px 0; border-bottom: 2px solid #eee; margin-bottom: 30px; }
        .result-card { background: white; border-radius: 15px; padding: 20px; margin-bottom: 15px; border-right: 6px solid #dc3545; transition: 0.3s; }
        .result-card:hover { transform: scale(1.01); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .score-box { background: #fdf2f2; color: #dc3545; padding: 10px 20px; border-radius: 12px; font-weight: bold; font-size: 1.2rem; min-width: 100px; text-align: center; }
        /* تمييز المراكز الثلاثة الأولى */
        .rank-1 { border-right-color: #ffc107; background: #fffdf2; }
        .rank-2 { border-right-color: #6c757d; }
        .rank-3 { border-right-color: #a52a2a; }
        .badge-category { background: #e9ecef; color: #495057; font-size: 0.8rem; border-radius: 5px; padding: 3px 10px; }
    </style>
</head>
<body>

<div class="header-section shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1">نتائج تحليل المجموعة #<?php echo $group_id; ?></h3>
            <p class="text-muted mb-0">تم ترتيب 60 مشروعاً حسب ملاءمة مهارات الطلاب</p>
        </div>
        <a href="admin_dashboard.php" class="btn btn-outline-danger rounded-pill px-4">
            <i class="fas fa-arrow-right me-2"></i> عودة للرئيسية
        </a>
    </div>
</div>

<div class="container pb-5">
    <?php foreach ($results as $index => $res): 
        $rankClass = ($index == 0) ? 'rank-1' : (($index == 1) ? 'rank-2' : (($index == 2) ? 'rank-3' : ''));
    ?>
    <div class="result-card shadow-sm d-flex justify-content-between align-items-center <?php echo $rankClass; ?> animate__animated animate__fadeInUp">
        <div class="d-flex align-items-center gap-4">
            <div class="text-muted fw-bold" style="font-size: 1.5rem; width: 40px;">#<?php echo $index + 1; ?></div>
            <div>
                <h5 class="fw-bold mb-1 text-dark"><?php echo $res['project_name']; ?></h5>
                <span class="badge-category"><?php echo $res['category']; ?></span>
            </div>
        </div>
        <div class="text-center">
            <div class="score-box">
                <?php echo number_format($res['match_score'], 1); ?>%
            </div>
            <small class="text-muted d-block mt-1">نسبة التوافق</small>
        </div>
    </div>
    <?php endforeach; ?>
</div>

</body>
</html>