<?php
session_start();
include("db.php");

// نأكد إن فيه رقم مجموعة مبعوث في الرابط
if (isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];

    try {
        // --- الخطوة 1: تنظيف الجدول من أي نتائج قديمة لنفس المجموعة ---
        // ده بيضمن إننا بنبدأ على نظافة ومفيش سطر بيتكرر
        $clear = $connection->prepare("DELETE FROM recommendations WHERE group_id = :gid");
        $clear->execute(['gid' => $group_id]);

        // --- الخطوة 2: جلب مهارات واهتمامات كل طلاب المجموعة ---
        $stmt = $connection->prepare("SELECT skill_name, type FROM student_choices WHERE group_id = :gid");
        $stmt->execute(['gid' => $group_id]);
        $group_choices = $stmt->fetchAll();

        $user_skills = [];
        $user_interests = [];

        foreach ($group_choices as $choice) {
            $val = trim(strtolower($choice['skill_name']));
            if ($choice['type'] == 'skill') {
                $user_skills[] = $val;
            } else {
                $user_interests[] = $val;
            }
        }

        // --- الخطوة 3: جلب الـ 60 مشروع من جدول projects ---
        $stmt = $connection->prepare("SELECT * FROM projects");
        $stmt->execute();
        $all_projects = $stmt->fetchAll();

        // --- الخطوة 4: حساب النسبة لكل مشروع وحفظه ---
        foreach ($all_projects as $project) {
            $pid = $project['project_id'];
            
            // تحويل نصوص المهارات والاهتمامات المطلوبة لمصفوفات
            $p_skills = array_map('trim', explode(',', strtolower($project['required_skills'])));
            $p_interests = array_map('trim', explode(',', strtolower($project['required_interests'])));

            // حساب نسبة المهارات (60%)
            $matched_s = count(array_intersect($user_skills, $p_skills));
            $total_s = count(array_filter($p_skills));
            $s_score = ($total_s > 0) ? ($matched_s / $total_s) * 60 : 0;

            // حساب نسبة الاهتمامات (40%)
            $matched_i = count(array_intersect($user_interests, $p_interests));
            $total_i = count(array_filter($p_interests));
            $i_score = ($total_i > 0) ? ($matched_i / $total_i) * 40 : 0;

            $final_match = $s_score + $i_score;

            // حفظ النتيجة في الجدول (دلوقتي مستحيل يتكرر لأننا مسحنا القديم في خطوة 1)
            $insert = $connection->prepare("INSERT INTO recommendations (group_id, project_id, match_score) 
                                            VALUES (:gid, :pid, :mscore)");
            $insert->execute([
                'gid' => $group_id, 
                'pid' => $pid, 
                'mscore' => $final_match
            ]);
        }

        // --- الخطوة 5: التحويل لصفحة العرض ---
        header("Location: view_recommendations.php?group_id=$group_id");
        exit();

    } catch (PDOException $e) {
        die("خطأ فني في قاعدة البيانات: " . $e->getMessage());
    }
} else {
    // لو حد دخل الصفحة من غير رقم مجموعة يرجعه للرئيسية
    header("Location: admin_dashboard.php");
    exit();
}
?>