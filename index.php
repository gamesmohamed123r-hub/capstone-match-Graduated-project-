<?php
include("db.php");
?>


<!-- ///////////////////////////////////////////////////////////////////// -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>capstone-project-Recommender</title>
                         
                           <!-- Bootstrap css -->
 <link rel="stylesheet" href="css/bootstrap.min.css">
                       <!-- fonts library bootstrap  -->
 <link rel="stylesheet" href="css/all.min.css">
                    <!-- animations library bootstrap -->
 <link rel="stylesheet" href="css/animate.min.css">
                     <!-- icons library bootstrap -->
 <link rel="stylesheet" href="css/bootstrap-icons.min.css">

                           <!-- link css folder -->
 <link rel="stylesheet" href="css/PR-css.css">
</head>


<body>
  
                      <!-- header navbar and icon  -->
<header>
  <nav id="home" class="navbar navbar-expand-md navbar-light">
    <div class="container"> <a class="homepage navbar-brand">
            <img src="imgs/Screenshot 2025-10-10 143923.png" alt="logo" width="130" height="auto">
        </a>
    </div>
  </nav>
</header>

                                 <!-- first section -->
<section class="first-section">
  <div class="container text-center">
    <h1 class="wow animate__animated animate__backInDown" data-wow-delay="0.8s">اعثر انت وفريقك علي مشروعكم المناسب</h1>
<p class="wow animate__animated animate__backInUp" data-wow-delay="0.2s">
    اكتشف المشاريع التي تتناسب مع مهاراتك واهتماماتك، حيث يقوم <br>
    هذا النظام بتحليل أفضل المسارات الأكاديمية المناسبة لك.<br>
    استكشف الفرص المتاحة، وحقّق طموحاتك، وتقدّم بثقة.
</p>
    <button id="button1s1" class="btn btn-danger rounded-pill small-btn px-4 py-1 ms-md-3" onclick="window.location.href='#abouttheweb'">شرح</button>
    <button id="button2s1" class="btn btn-danger rounded-pill small-btn px-4 py-1 ms-md-3" onclick="window.location.href=' login.php'">تسجيل</button>
  </div>
</section>

                                    <!-- second section About -->
<section class="About-section">
  <div class="container text-center">
    <h1 id="abouttheweb">مرحبًا بكم في capstone-recommender-projects</h1>
    <p>
      تم إنشاء هذه المنصة لمساعدة الطلاب مثلكم على اختيار مشروع التخرج الأنسب لكم.<br>
      يقوم الموقع بتحليل اهتماماتك أنت وفريقك، بالإضافة إلى المجالات التي تفضلونها<br>
      ومهاراتكم وخلفيتكم الدراسية، ثم يجمع هذه البيانات ويعالجها بدقة.<br>
      بعد ذلك، يتم تقديم نتائج تحليل شاملة تساعد في تحديد أفضل مشروع مناسب لكم.<br>
      كما يمكن إرسال هذه النتائج إلى الدكتور المسؤول لدعم عملية الاختيار واتخاذ القرار.<br>
      يمكنك استكشاف العديد من الفرص، حفظ المشاريع المفضلة، والعمل عليها بثقة.<br>
      سواء كنتم في بداية الطريق أو تبحثون عن الفكرة المثالية، فإن<br>
      هذا الموقع هو دليلكم للوصول إلى مشروع التخرج المناسب بكل سهولة واحترافية.<br>
      إنه ليس مجرد موقع بحث، بل مساعد ذكي يدعمك أنت وفريقك في كل خطوة.<br>
    </p>
<hr>
    <div class="names">
      <u><mark>mohamed mostafa ahmed hassanin</mark></u><br>
      <u><mark>khalid ahmed mohamed abo elazem elgamal</mark></u><br>
  <u><mark>mahmoud elprince mohamed mohamded elsanbakte</mark></u><br>
  <u><mark>mohamed uesry mohamady mahmoud</mark></u><br>
  <u><mark>mohamed elsaid mohamed mohamed</mark></u>
</div>

       <button id="button2ss2" class="btn rounded-pill small-btn px-4 py-1 ms-md-3" onclick="window.location.href='#home'">الي فوق</button>
  </div>
</section> 



                                <!-- Bootstrap js -->
 <!-- 1-->   <script src="javascript/jquery.slim.min.js"></script>
 <!-- 2-->   <script src="javascript/bootstrap.bundle.min.js"></script>
 <!-- 3-->   <script src="javascript/all.min.js"></script>
 <!-- 4-->  <script src="javascript/wow.min.js"></script>
                              <!-- link js -->
<script src="javascript/PR-js.js" defer></script>
</body>


</html>