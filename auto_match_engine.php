<?php
// منع إعادة استدعاء الجلسة لو الملف تم عمل include له
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("db.php");

try {
    // 1. جلب الطلاب المنتظرين
    $query = "SELECT student_id, GROUP_CONCAT(skill_name ORDER BY skill_name) as student_fingerprint 
              FROM student_choices 
              WHERE group_id = '0' 
              GROUP BY student_id";
    
    $stmt = $connection->query($query);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // لو مفيش طلاب، بنوقف التنفيذ بهدوء عشان ميحصلش Redirect loop
    if (empty($students)) {
        if (basename($_SERVER['PHP_SELF']) == 'auto_match_engine.php') {
            header("Location: admin_dashboard.php?status=no_pending");
            exit();
        }
        return; // لو استدعاء من Dashboard يخرج بس
    }

    $similarity_groups = [];
    foreach ($students as $s) {
        $similarity_groups[$s['student_fingerprint']][] = $s['student_id'];
    }

    $matched_count = 0;

    // 2. البدء في التسكين
    foreach ($similarity_groups as $fingerprint => $student_ids) {
        if (count($student_ids) >= 3) {
            
            // جلب المشاريع المتاحة في كل لفة للتأكد إن المشروع متأخدش للمجموعة اللي قبلها
            $available_projects = $connection->query("SELECT * FROM projects WHERE is_assigned = 0")->fetchAll();
            $best_pid = null;
            $max_score = -1;
            $team_skills = explode(',', strtolower($fingerprint));

            foreach ($available_projects as $project) {
                $proj_skills = array_map('trim', explode(',', strtolower($project['required_interests'])));
                $intersection = count(array_intersect($team_skills, $proj_skills));
                
                if ($intersection > $max_score) {
                    $max_score = $intersection;
                    $best_pid = $project['project_id'];
                }
            }

            if ($best_pid) {
                // حساب الرقم الجديد بدقة في كل لفة
                $maxG = $connection->query("SELECT MAX(CAST(SUBSTRING(group_id, 7) AS UNSIGNED)) as max_id FROM recommendations")->fetch();
                $next_id = ($maxG['max_id'] ?? 0) + 1;
                $new_group_id = "Group_" . $next_id;

                $connection->beginTransaction();
                
                // تحديث الطلاب
                $updateS = $connection->prepare("UPDATE student_choices SET group_id = ? WHERE student_id = ?");
                foreach ($student_ids as $sid) {
                    $updateS->execute([$new_group_id, $sid]);
                }

                // حجز المشروع
                $updateP = $connection->prepare("UPDATE projects SET is_assigned = 1, assigned_group_id = ? WHERE project_id = ?");
                $updateP->execute([$new_group_id, $best_pid]);

                // تسجيل التوصية
                $res = $connection->prepare("INSERT INTO recommendations (group_id, project_id, match_score) VALUES (?, ?, ?)");
                $res->execute([$new_group_id, $best_pid, 100]);
                
                $connection->commit();
                $matched_count++;
            }
        }
    }

    // التوجيه فقط لو تم تشغيل الملف بشكل مباشر ومستقل
    if (basename($_SERVER['PHP_SELF']) == 'auto_match_engine.php') {
        header("Location: admin_dashboard.php?status=success&matched=" . $matched_count);
        exit();
    }

} catch (PDOException $e) {
    if ($connection->inTransaction()) $connection->rollBack();
    die("خطأ: " . $e->getMessage());
}