<?php 
session_start();
include("db.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // trim بتشيل أي مسافات في الأول أو الآخر
    $username = trim($_POST['user']);
    $role = $_POST['role'];

    if ($role == 'admin') {
        $password = $_POST['pass'];
        // التأكد من بيانات الأدمن
        $stmt = $connection->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
        $stmt->execute([$username, $password]);
        $admin = $stmt->fetch();

        if ($admin) {
            $_SESSION['admin_user'] = $admin['username'];
            $_SESSION['role'] = 'admin';
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "خطأ في اسم المستخدم أو كلمة المرور!";
        }
    } else {
        // البحث عن الطالب - استخدمنا LIKE مع % عشان لو فيه مسافات بسيطة في الداتابيز
        $stmt = $connection->prepare("SELECT * FROM students WHERE student_name LIKE ?");
        $stmt->execute(["%$username%"]);
        $student = $stmt->fetch();

        if ($student) {
            // تخزين بيانات الطالب في الجلسة (Session)
            $_SESSION['student_id'] = $student['id']; 
            $_SESSION['student_name'] = $student['student_name']; // الاسم الموحد في القاعدة
            $_SESSION['role'] = 'student';
            
            header("Location: interests.php");
            exit();
        } else {
            $error = "الاسم غير مسجل في الكشوف! راجع الإدارة أو تأكد من كتابة الاسم صح.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام الترشيح</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/animate.min.css">
    <style>
        body { background-color: #F1F5FF; font-family: 'Segoe UI', sans-serif; }
        .login-container { margin-top: 80px; }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .btn-danger { background-color: #E63131; border: none; transition: 0.3s; }
        .btn-danger:hover { background-color: #c42929; transform: translateY(-2px); }
        .role-selector { background: #f8f9fa; padding: 10px; border-radius: 15px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4 animate__animated animate__fadeIn">
                <div class="text-center mb-4">
                    <img src="imgs/logo.png" alt="logo" width="120" class="mb-3" onerror="this.style.display='none'">
                    <h3 class="fw-bold">مرحباً بك</h3>
                    <p class="text-muted small">سجل دخولك للبدء في اختيار رغباتك</p>
                </div>

                <?php if($error): ?>
                    <div class="alert alert-danger text-center py-2 animate__animated animate__shakeX">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="role-selector text-center">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="role" id="roleStudent" value="student" checked onclick="toggleFields('student')">
                            <label class="btn btn-outline-danger" for="roleStudent">طالب</label>
                            
                            <input type="radio" class="btn-check" name="role" id="roleAdmin" value="admin" onclick="toggleFields('admin')">
                            <label class="btn btn-outline-danger" for="roleAdmin">دكتور / Admin</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label id="nameLabel" class="form-label fw-bold">الاسم الكامل (كما هو مسجل في الكشوف)</label>
                        <input type="text" name="user" class="form-control" placeholder="أدخل اسمك" required autocomplete="off">
                    </div>

                    <div class="mb-4" id="passField" style="display:none;">
                        <label class="form-label fw-bold">كلمة المرور</label>
                        <input type="password" name="pass" class="form-control" placeholder="كلمة المرور الخاصة بك">
                    </div>

                    <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 fw-bold">دخول النظام</button>
                    <a href="index.php" class="btn btn-secondary w-100 rounded-pill py-2 fw-bold my-3">رجوع</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFields(role) {
        const passField = document.getElementById('passField');
        const nameLabel = document.getElementById('nameLabel');
        const passInput = document.querySelector('input[name="pass"]');
        if (role === 'admin') {
            passField.style.display = 'block';
            nameLabel.innerText = 'اسم المستخدم (Admin)';
            passInput.setAttribute('required', 'required');
        } else {
            passField.style.display = 'none';
            nameLabel.innerText = 'الاسم الكامل (كما هو مسجل في الكشوف)';
            passInput.removeAttribute('required');
        }
    }
</script>
</body>
</html>