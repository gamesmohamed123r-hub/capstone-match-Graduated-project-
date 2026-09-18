<?php
session_start();
include("db.php"); // عشان نضمن الاتصال بالداتابيز لو احتجنا نعرض بيانات تانية

// التأكد إن الطالب مسجل دخول
if (!isset($_SESSION['student_name'])) {
    header("Location: login.php"); // التوجيه لصفحة اللوجن اللي إنت عاملها
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إتمام التسجيل - Project Recommender</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/animate.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            direction: rtl;
        }
        .waiting-card {
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            text-align: center;
            max-width: 600px;
            width: 90%;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
        }
        p {
            color: #666;
            font-size: 1.2rem;
            line-height: 1.6;
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #dc3545;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
            margin: 30px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .btn-home {
            background-color: #dc3545;
            color: white;
            padding: 10px 30px;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-home:hover {
            background-color: #c82333;
            color: white;
        }
    </style>
</head>
<body>

<div class="waiting-card wow animate__animated animate__fadeIn">
    <div class="success-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <h1>تم تسجيل بياناتك بنجاح!</h1>
    <p>
        يا <strong><?php echo $_SESSION['student_name']; ?></strong>، لقد استلمنا اهتماماتك ومهاراتك. 
        <br>
        برجاء الانتظار حتى يقوم باقي أعضاء مجموعتك بتسجيل بياناتهم، ليتمكن المشرف (الدكتور) من مراجعتها وتحديد المشروع الأنسب لكم.
    </p>
    
    <div class="loader"></div>
    
    <p class="small text-muted">سيتم إخطارك فور صدور النتيجة.</p>
    
    <a href="index.php" class="btn-home">العودة للرئيسية</a>
</div>

<script src="javascript/wow.min.js"></script>
<script>new WOW().init();</script>

</body>
</html>