<?php
session_start();
include("db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$skills = $_POST['skills'] ?? [];

if (!empty($skills)) {
    try {
        $connection->beginTransaction();

        // 1. مسح أي مهارات قديمة للطالب (Type = skill)
        $del = $connection->prepare("DELETE FROM student_choices WHERE student_id = ? AND type = 'skill'");
        $del->execute([$student_id]);

        // 2. إدخال المهارات الجديدة
        $ins = $connection->prepare("INSERT INTO student_choices (student_id, student_name, skill_name, type, group_id) VALUES (?, ?, ?, 'skill', '0')");
        foreach ($skills as $skill) {
            $ins->execute([$student_id, $_SESSION['student_name'], $skill]);
        }

        $connection->commit();

        // 3. تشغيل المحرك الذكي لتكوين المجموعات (التشابه في المهارات + الاهتمامات)
        processGrouping($connection);

        echo "<script>alert('تم تسجيل بياناتك بنجاح! سيتم إخطارك عند تكوين المجموعة.'); window.location.href='index.php';</script>";

    } catch (Exception $e) {
        $connection->rollBack();
        echo "خطأ: " . $e->getMessage();
    }
}

function processGrouping($connection) {
    // جلب الطلاب غير المسكنين وتجميع كل اختياراتهم (Skills & Interests) في سطر واحد لكل طالب
    $query = "SELECT student_id, GROUP_CONCAT(skill_name ORDER BY skill_name) as all_choices 
              FROM student_choices 
              WHERE group_id = '0' 
              GROUP BY student_id";
    
    $stmt = $connection->query($query);
    $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // تجميع الطلاب حسب تطابق الاختيارات بالظبط
    $groups_map = [];
    foreach ($candidates as $row) {
        $groups_map[$row['all_choices']][] = $row['student_id'];
    }

    foreach ($groups_map as $choices_str => $student_ids) {
        // الشرط بتاعك: 3 طلاب أو أكثر ليهم نفس الاختيارات
        if (count($student_ids) >= 3) {
            
            // البحث عن أفضل مشروع متاح
            $user_choices_array = explode(',', $choices_str);
            $available_projects = $connection->query("SELECT * FROM projects WHERE is_assigned = 0")->fetchAll();
            
            $best_project_id = null;
            $highest_score = -1;

            foreach ($available_projects as $project) {
                $req_array = array_map('trim', explode(',', strtolower($project['required_interests'])));
                $user_array = array_map('trim', array_map('strtolower', $user_choices_array));
                
                $match_count = count(array_intersect($user_array, $req_array));
                
                if ($match_count > $highest_score) {
                    $highest_score = $match_count;
                    $best_project_id = $project['project_id'];
                }
            }

            // إذا وجدنا مشروعاً مناسباً، نقوم بعملية التسكين
            if ($best_project_id) {
                $new_group_id = "GRP_" . rand(100, 999) . time(); // توليد رقم مجموعة فريد

                // تحديث الطلاب ليصبحوا في المجموعة الجديدة
                $updateStudent = $connection->prepare("UPDATE student_choices SET group_id = ? WHERE student_id = ?");
                foreach ($student_ids as $sid) {
                    $updateStudent->execute([$new_group_id, $sid]);
                }

                // حجز المشروع للمجموعة
                $updateProject = $connection->prepare("UPDATE projects SET is_assigned = 1, assigned_group_id = ? WHERE project_id = ?");
                $updateProject->execute([$new_group_id, $best_project_id]);

                // إضافة السجل في جدول التوصيات ليظهر في الـ Dashboard
                $insertRec = $connection->prepare("INSERT INTO recommendations (group_id, project_id, match_score) VALUES (?, ?, ?)");
                $insertRec->execute([$new_group_id, $best_project_id, 100]);
            }
        }
    }
}
?>