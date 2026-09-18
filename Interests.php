<?php
session_start();
include("db.php");
if (!isset($_SESSION['student_name'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Recommender - Interests</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/animate.min.css">
    <link rel="stylesheet" href="css/bootstrap-icons.min.css">

    <style>
        body { background-color: #f4f7fe; font-family: 'Segoe UI', sans-serif; }
        
        /* تصليح الـ Navbar عشان ميغطيش العنوان */
        .navbar { z-index: 1050; }

        .first-interests {
            margin-top: 180px; /* زودت المسافة عشان العنوان ينزل تحت الناف بار */
            padding-bottom: 60px;
        }

        .first-interests h1 {
            font-size: 50px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 40px;
            text-align: center;
        }

        #searchform {
            max-width: 500px;
            margin: 0 auto 40px auto;
        }

        .small-search {
            border-radius: 10px !important;
            padding: 10px 20px !important;
            border: 1px solid #ccc !important;
        }

        /* نظام الليستة (تحت بعض) */
        #list {
            display: flex;
            flex-direction: column; /* يخليهم تحت بعض بالظبط */
            align-items: center;
            gap: 15px;
            max-width: 800px;
            margin: 0 auto;
        }

        #list label {
            display: flex;
            align-items: center;
            justify-content: space-between; /* يخلي الكلام في ناحية والبوكس في ناحية */
            background: white;
            padding: 15px 30px;
            border-radius: 10px;
            width: 100%; /* ياخد العرض كله عشان يبقوا متساويين */
            border: 1px solid #ddd;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
        }

        #list input[type="checkbox"] {
            width: 22px;
            height: 22px;
            accent-color: #E63131;
            cursor: pointer;
        }

        .buttons-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
        }

        .btn-custom {
            width: 150px;
            padding: 10px;
            border-radius: 30px;
            font-weight: bold;
            border: 2px solid #E63131;
        }

        .btn-back { background: white; color: #E63131; }
        .btn-next { background: #E63131; color: white; }

        .hidden { display: none !important; }
    </style>
</head>
<body>

<header>
  <nav class="navbar navbar-expand-md navbar-light fixed-top bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand">
        <img src="imgs/Screenshot 2025-10-10 143923.png" alt="logo" width="130">
      </a>
      <div class="ms-auto d-flex align-items-center">
          <span class="me-3">مرحباً: <b class="text-danger"><?php echo $_SESSION['student_name']; ?></b></span>
          <a href="logout.php" class="btn btn-outline-danger px-4 py-1 small-btn">خروج</a>
      </div>
    </div>
  </nav>
</header>

<section class="first-interests">
  <div class="container">
    <h1 class="wow animate__animated animate__fadeInDown">Your Interests</h1>

    <div id="searchform">
      <input id="searchbox" class="form-control small-search" type="search" placeholder="ابحث عن اهتماماتك..."/>
    </div>

    <form action="save_interests.php" method="POST">
        <div id="list">
          <label>Accessibility <input type="checkbox" name="interests[]" value="Accessibility"></label>
          <label>AI (Artificial Intelligence) <input type="checkbox" name="interests[]" value="AI"></label>
          <label>Automation <input type="checkbox" name="interests[]" value="Automation"></label>
          <label>Blockchain <input type="checkbox" name="interests[]" value="Blockchain"></label>
          <label>Business <input type="checkbox" name="interests[]" value="Business"></label>
          <label>Computer Vision <input type="checkbox" name="interests[]" value="Computer Vision"></label>
          <label>Cybersecurity <input type="checkbox" name="interests[]" value="Cybersecurity"></label>
          <label>Data Analysis <input type="checkbox" name="interests[]" value="Data Analysis"></label>
          <label>Data Protection <input type="checkbox" name="interests[]" value="Data Protection"></label>
          <label>Data Science <input type="checkbox" name="interests[]" value="Data Science"></label>
          <label>Data Visualization <input type="checkbox" name="interests[]" value="Data Visualization"></label>
          <label>Design <input type="checkbox" name="interests[]" value="Design"></label>
          <label>Desktop Apps <input type="checkbox" name="interests[]" value="Desktop Apps"></label>
          <label>DevOps <input type="checkbox" name="interests[]" value="DevOps"></label>
          <label>Document Processing <input type="checkbox" name="interests[]" value="Document Processing"></label>
          <label>E-commerce <input type="checkbox" name="interests[]" value="E-commerce"></label>
          <label>Education <input type="checkbox" name="interests[]" value="Education"></label>
          <label>Finance <input type="checkbox" name="interests[]" value="Finance"></label>
          <label>Forecasting <input type="checkbox" name="interests[]" value="Forecasting"></label>
          <label>Game Development <input type="checkbox" name="interests[]" value="Game Development"></label>
          <label>Healthcare <input type="checkbox" name="interests[]" value="Healthcare"></label>
          <label>Image Processing <input type="checkbox" name="interests[]" value="Image Processing"></label>
          <label>IoT (Internet of Things) <input type="checkbox" name="interests[]" value="IoT"></label>
          <label>Language Processing <input type="checkbox" name="interests[]" value="Language Processing"></label>
          <label>Mobile Development <input type="checkbox" name="interests[]" value="Mobile Development"></label>
          <label>Networking <input type="checkbox" name="interests[]" value="Networking"></label>
          <label>NLP (Natural Language Processing) <input type="checkbox" name="interests[]" value="NLP"></label>
          <label>Operating Systems <input type="checkbox" name="interests[]" value="Operating Systems"></label>
          <label>Programming <input type="checkbox" name="interests[]" value="Programming"></label>
          <label>Risk Analysis <input type="checkbox" name="interests[]" value="Risk Analysis"></label>
          <label>Robotics <input type="checkbox" name="interests[]" value="Robotics"></label>
          <label>Security <input type="checkbox" name="interests[]" value="Security"></label>
          <label>Signal Processing <input type="checkbox" name="interests[]" value="Signal Processing"></label>
          <label>Smart Systems <input type="checkbox" name="interests[]" value="Smart Systems"></label>
          <label>Social Media <input type="checkbox" name="interests[]" value="Social Media"></label>
          <label>System Programming <input type="checkbox" name="interests[]" value="System Programming"></label>
          <label>Text Processing <input type="checkbox" name="interests[]" value="Text Processing"></label>
          <label>Video Processing <input type="checkbox" name="interests[]" value="Video Processing"></label>
          <label>VR/AR (Virtual Reality / Augmented Reality) <input type="checkbox" name="interests[]" value="VR/AR"></label>
          <label>Web Development <input type="checkbox" name="interests[]" value="Web Development"></label>
        </div>


      <div class="buttons-container wow animate__animated animate__fadeInUp">
    <button type="submit" class="btn-custom btn-next" style="width: 200px;">Next Step</button>
</div>
    </form>
  </div>
</section>

<script>
    document.getElementById('searchbox').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let labels = document.querySelectorAll('#list label');
        labels.forEach(label => {
            if (label.textContent.toLowerCase().includes(filter)) {
                label.classList.remove('hidden');
            } else {
                label.classList.add('hidden');
            }
        });
    });
</script>

<script src="javascript/jquery.slim.min.js"></script>
<script src="javascript/bootstrap.bundle.min.js"></script>
<script src="javascript/wow.min.js"></script>
<script>new WOW().init();</script>
</body>
</html>