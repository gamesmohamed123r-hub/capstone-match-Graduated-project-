<?php
session_start();
include("db.php");
if (!isset($_SESSION['student_name'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Recommender - Skills</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/animate.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; direction: rtl; }
        .header-section { padding: 15px 30px; display: flex; justify-content: flex-end; align-items: center; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .user-info { display: flex; align-items: center; gap: 15px; }
        .logout-btn { border: 1px solid #dc3545; color: #dc3545; border-radius: 8px; padding: 5px 25px; text-decoration: none; font-size: 14px; }
        .main-title { font-size: 3rem; font-weight: 800; color: #212529; margin-top: 40px; text-align: center; }
        .search-container { max-width: 600px; margin: 20px auto 40px; }
        .search-input { border-radius: 10px; padding: 12px; text-align: center; border: 1px solid #ddd; }
        
        /* تصميم القائمة لتكون كروت زي الاهتمامات */
        #list { max-width: 800px; margin: 0 auto; }
        #list label {
            background: white;
            border-radius: 12px;
            padding: 15px 25px;
            margin-bottom: 10px;
            display: flex;
            flex-direction: row-reverse;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #e9ecef;
            cursor: pointer;
            transition: 0.2s;
        }
        #list label:hover { border-color: #dc3545; background-color: #fffafa; }
        #list input[type="checkbox"] { width: 20px; height: 20px; accent-color: #dc3545; }
        #list span { font-size: 1.1rem; color: #333; }
        
        .btn-next { background-color: #dc3545; color: white; padding: 12px 60px; border-radius: 10px; border: none; font-weight: bold; display: block; margin: 40px auto; }
    </style>
</head>
<body>

<div class="header-section">
    <div class="user-info">
        <span>مرحباً: <strong class="text-danger"><?php echo $_SESSION['student_name']; ?></strong></span>
        <a href="logout.php" class="logout-btn">خروج</a>
        <img src="imgs/logo.png" alt="" width="40"> 
    </div>
</div>

<div class="container">
    <h1 class="main-title">Your Skills</h1>
    
    <div class="search-container">
        <input type="text" id="searchbox" class="form-control search-input" placeholder="...ابحث عن مهاراتك">
    </div>

    <form action="save_skills.php" method="POST">
        <div id="list">
            <label><input type="checkbox" name="skills[]" value="Accessibility"> <span>Accessibility</span></label>
            <label><input type="checkbox" name="skills[]" value="Arabic"> <span>Arabic</span></label>
            <label><input type="checkbox" name="skills[]" value="Arduino"> <span>Arduino</span></label>
            <label><input type="checkbox" name="skills[]" value="AST"> <span>AST</span></label>
            <label><input type="checkbox" name="skills[]" value="Audio Processing"> <span>Audio Processing</span></label>
            <label><input type="checkbox" name="skills[]" value="Automation"> <span>Automation</span></label>
            <label><input type="checkbox" name="skills[]" value="Bash"> <span>Bash</span></label>
            <label><input type="checkbox" name="skills[]" value="Blockchain"> <span>Blockchain</span></label>
            <label><input type="checkbox" name="skills[]" value="C#"> <span>C#</span></label>
            <label><input type="checkbox" name="skills[]" value="C++"> <span>C++</span></label>
            <label><input type="checkbox" name="skills[]" value="Code Analysis"> <span>Code Analysis</span></label>
            <label><input type="checkbox" name="skills[]" value="Computer Vision"> <span>Computer Vision</span></label>
            <label><input type="checkbox" name="skills[]" value="Cryptography"> <span>Cryptography</span></label>
            <label><input type="checkbox" name="skills[]" value="Data Analysis"> <span>Data Analysis</span></label>
            <label><input type="checkbox" name="skills[]" value="Data Processing"> <span>Data Processing</span></label>
            <label><input type="checkbox" name="skills[]" value="Data Science"> <span>Data Science</span></label>
            <label><input type="checkbox" name="skills[]" value="Data Visualization"> <span>Data Visualization</span></label>
            <label><input type="checkbox" name="skills[]" value="Database"> <span>Database</span></label>
            <label><input type="checkbox" name="skills[]" value="Deep Learning"> <span>Deep Learning</span></label>
            <label><input type="checkbox" name="skills[]" value="Desktop Apps"> <span>Desktop Apps</span></label>
            <label><input type="checkbox" name="skills[]" value="DevOps"> <span>DevOps</span></label>
            <label><input type="checkbox" name="skills[]" value="Docker"> <span>Docker</span></label>
            <label><input type="checkbox" name="skills[]" value="Document Processing"> <span>Document Processing</span></label>
            <label><input type="checkbox" name="skills[]" value="Email Protocols"> <span>Email Protocols</span></label>
            <label><input type="checkbox" name="skills[]" value="Flutter"> <span>Flutter</span></label>
            <label><input type="checkbox" name="skills[]" value="Image Processing"> <span>Image Processing</span></label>
            <label><input type="checkbox" name="skills[]" value="Java"> <span>Java</span></label>
            <label><input type="checkbox" name="skills[]" value="JavaScript"> <span>JavaScript</span></label>
            <label><input type="checkbox" name="skills[]" value="Language Processing"> <span>Language Processing</span></label>
            <label><input type="checkbox" name="skills[]" value="Linux"> <span>Linux</span></label>
            <label><input type="checkbox" name="skills[]" value="Machine Learning"> <span>Machine Learning</span></label>
            <label><input type="checkbox" name="skills[]" value="MediaPipe"> <span>MediaPipe</span></label>
            <label><input type="checkbox" name="skills[]" value="Mobile Development"> <span>Mobile Development</span></label>
            <label><input type="checkbox" name="skills[]" value="NLP"> <span>NLP</span></label>
            <label><input type="checkbox" name="skills[]" value="Network Analysis"> <span>Network Analysis</span></label>
            <label><input type="checkbox" name="skills[]" value="Network Security"> <span>Network Security</span></label>
            <label><input type="checkbox" name="skills[]" value="Networking"> <span>Networking</span></label>
            <label><input type="checkbox" name="skills[]" value="Neural Networks"> <span>Neural Networks</span></label>
            <label><input type="checkbox" name="skills[]" value="OCR"> <span>OCR</span></label>
            <label><input type="checkbox" name="skills[]" value="OpenCV"> <span>OpenCV</span></label>
            <label><input type="checkbox" name="skills[]" value="Pandas"> <span>Pandas</span></label>
            <label><input type="checkbox" name="skills[]" value="Python"> <span>Python</span></label>
            <label><input type="checkbox" name="skills[]" value="RFID"> <span>RFID</span></label>
            <label><input type="checkbox" name="skills[]" value="Robotics"> <span>Robotics</span></label>
            <label><input type="checkbox" name="skills[]" value="Security"> <span>Security</span></label>
            <label><input type="checkbox" name="skills[]" value="Security Tools"> <span>Security Tools</span></label>
            <label><input type="checkbox" name="skills[]" value="Sensors"> <span>Sensors</span></label>
            <label><input type="checkbox" name="skills[]" value="Signal Processing"> <span>Signal Processing</span></label>
            <label><input type="checkbox" name="skills[]" value="Smart Contracts"> <span>Smart Contracts</span></label>
            <label><input type="checkbox" name="skills[]" value="Solidity"> <span>Solidity</span></label>
            <label><input type="checkbox" name="skills[]" value="Speech Recognition"> <span>Speech Recognition</span></label>
            <label><input type="checkbox" name="skills[]" value="System Monitoring"> <span>System Monitoring</span></label>
            <label><input type="checkbox" name="skills[]" value="System Programming"> <span>System Programming</span></label>
            <label><input type="checkbox" name="skills[]" value="TensorFlow"> <span>TensorFlow</span></label>
            <label><input type="checkbox" name="skills[]" value="Testing Frameworks"> <span>Testing Frameworks</span></label>
            <label><input type="checkbox" name="skills[]" value="Text Processing"> <span>Text Processing</span></label>
            <label><input type="checkbox" name="skills[]" value="Transformers"> <span>Transformers</span></label>
            <label><input type="checkbox" name="skills[]" value="UI/UX"> <span>UI/UX</span></label>
            <label><input type="checkbox" name="skills[]" value="Unity3D"> <span>Unity3D</span></label>
            <label><input type="checkbox" name="skills[]" value="VR/AR Development"> <span>VR/AR Development</span></label>
            <label><input type="checkbox" name="skills[]" value="Web Development"> <span>Web Development</span></label>
            <label><input type="checkbox" name="skills[]" value="Web Scraping"> <span>Web Scraping</span></label>
            <label><input type="checkbox" name="skills[]" value="Web3"> <span>Web3</span></label>
            <label><input type="checkbox" name="skills[]" value="WebAuthn"> <span>WebAuthn</span></label>
        </div>
<div class="d-flex justify-content-center align-items-center gap-3 mt-5 mb-5">
            <button type="button" class="btn btn-outline-danger rounded-pill px-5 fw-bold" 
                    onclick="window.location.href='interests.php'" 
                    style="padding: 12px 60px;">
                Back
            </button>
            
            <button type="submit" class="btn-next m-0">
                Next
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('searchbox').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let labels = document.querySelectorAll('#list label');
        labels.forEach(label => {
            let text = label.querySelector('span').textContent.toLowerCase();
            label.style.display = text.includes(filter) ? "flex" : "none";
        });
    });
</script>
</body>
</html>