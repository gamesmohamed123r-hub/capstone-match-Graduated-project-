<?php
include("db.php");

if (isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];

    try {
        // 1. مسح النتائج من جدول التوصيات
        $stmt1 = $connection->prepare("DELETE FROM recommendations WHERE group_id = :gid");
        $stmt1->execute(['gid' => $group_id]);

        // 2. مسح الاختيارات من جدول مهارات الطلاب
        // السطر ده هو اللي هيخلي المربع يختفي من الـ Dashboard
        $stmt2 = $connection->prepare("DELETE FROM student_choices WHERE group_id = :gid");
        $stmt2->execute(['gid' => $group_id]);

        // رجوع لصفحة الإدارة بعد المسح
        header("Location: admin_dashboard.php");
        exit();

    } catch (PDOException $e) {
        die("خطأ: " . $e->getMessage());
    }
}
?>