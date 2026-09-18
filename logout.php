<?php
session_start();
session_unset(); // بيمسح كل البيانات اللي في السشن
session_destroy(); // بيقفل الجلسة تماماً
header("Location: login.php"); // بيرجعه لصفحة الدخول
exit();
?>