<?php
session_start();
include("db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$interests = $_POST['interests'] ?? [];

if (!empty($interests)) {
    try {
        $connection->beginTransaction();

        // 1. مسح الاهتمامات القديمة
        $del = $connection->prepare("DELETE FROM student_choices WHERE student_id = ? AND type = 'interest'");
        $del->execute([$student_id]);

        // 2. إدخال الاهتمامات الجديدة
        $ins = $connection->prepare("INSERT INTO student_choices (student_id, student_name, skill_name, type, group_id) VALUES (?, ?, ?, 'interest', '0')");
        foreach ($interests as $item) {
            $ins->execute([$student_id, $_SESSION['student_name'], $item]);
        }

        $connection->commit(); // بنقفل العملية هنا عشان الداتابيز تفوق

        // 3. التوجيه لصفحة المهارات (Skills) مباشرة
        // بلاش تنادي المحرك هنا عشان ميتقلش الصفحة، سيب الأدمن يشغله من الـ Dashboard
        header("Location: skills.php");
        exit();

    } catch (Exception $e) {
        if ($connection->inTransaction()) {
            $connection->rollBack();
        }
        die("خطأ في الحفظ: " . $e->getMessage());
    }
} else {
    // لو مبعتش اهتمامات رجعه تاني
    header("Location: interests.php?error=empty");
    exit();
}
?>