<?php
  $page = 'Help & Tutorials';
  session_start();
  $log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
  if (!isset($log)) { header('Location: login.php'); }
  error_reporting(0);
  include_once('../inc/config.php');
?>
<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <?php 
      $page = 'Help & Tutorials';
      $clinic = 'Asia Dental Clinic';
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>
  </head>
  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include_once('../inc/navigation_menu.php'); ?>
        <div class="layout-page">
          <?php include_once('../inc/navbar.php'); ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <!-- Heading -->
              <div class="d-flex justify-content-between">
                <div class="me-auto">
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light">Support /</span> Help & Tutorials
                  </h4>
                </div>
              </div>

              <!-- Contact Cards -->
              <div class="row mb-4">
                <!-- Facebook Contact -->
                <div class="col-md-4 mb-3">
                  <div class="card h-100">
                    <div class="card-body text-center">
                      <div class="avatar avatar-md mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-primary">
                          <i class="bx bxl-facebook-square fs-3"></i>
                        </span>
                      </div>
                      <h5 class="card-title mb-1">Contact via Facebook</h5>
                      <p class="card-text">Get quick support through our Facebook page</p>
                      <a href="https://facebook.com/asiadentalclinic" target="_blank" class="btn btn-outline-primary">
                        <i class="bx bxl-facebook-square me-1"></i> Message Us
                      </a>
                    </div>
                  </div>
                </div>
                
                <!-- Gmail Contact -->
                <div class="col-md-4 mb-3">
                  <div class="card h-100">
                    <div class="card-body text-center">
                      <div class="avatar avatar-md mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-danger">
                          <i class="bx bxl-gmail fs-3"></i>
                        </span>
                      </div>
                      <h5 class="card-title mb-1">Email Support</h5>
                      <p class="card-text">Send us an email for detailed inquiries</p>
                      <a href="mailto:support@asiadental.com" class="btn btn-outline-danger">
                        <i class="bx bx-envelope me-1"></i> Email Us
                      </a>
                    </div>
                  </div>
                </div>
                
                <!-- Telegram Contact -->
                <div class="col-md-4 mb-3">
                  <div class="card h-100">
                    <div class="card-body text-center">
                      <div class="avatar avatar-md mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-info">
                          <i class="bx bxl-telegram fs-3"></i>
                        </span>
                      </div>
                      <h5 class="card-title mb-1">Telegram Support</h5>
                      <p class="card-text">Get instant help through our Telegram channel</p>
                      <a href="https://t.me/asiadentalclinic" target="_blank" class="btn btn-outline-info">
                        <i class="bx bxl-telegram me-1"></i> Chat Now
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Category Title -->
              <div class="row">
                <div class="col-12 mb-3">
                  <h3 class="fw-bold">General Tutorials</h3>
                  <p>Browse our wide range of general tutorials for dental management system users.</p>
                </div>
              </div>

              <!-- Video Grid -->
              <div class="row tutorial-grid">
                <?php
                  // Get videos from database
                  $sql = "SELECT * FROM tutorial_videos ORDER BY date_added DESC";
                  $result = $CON->query($sql);
                  
                  if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                      // Extract YouTube video ID
                      preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $row['video_link'], $matches);
                      $youtube_id = isset($matches[1]) ? $matches[1] : '';
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                  <div class="card h-100">
                    <div class="card-img-top ratio ratio-16x9">
                      <iframe 
                        src="https://www.youtube.com/embed/<?php echo $youtube_id; ?>" 
                        title="<?php echo $row['title']; ?>" 
                        allowfullscreen
                      ></iframe>
                    </div>
                    <div class="card-body d-flex flex-column">
                      <h5 class="card-title"><?php echo $row['title']; ?></h5>
                      <p class="card-text flex-grow-1"><?php echo $row['description']; ?></p>
                      <div class="mt-auto pt-2 border-top">
                        <span class="badge bg-label-primary"><?php echo $row['category']; ?></span>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
                    }
                  } else {
                ?>
                <div class="col-12">
                  <div class="card">
                    <div class="card-body text-center py-5">
                      <div class="mb-3">
                        <i class="bx bx-video-off fs-1 text-muted"></i>
                      </div>
                      <h5>No tutorials available</h5>
                      <p>Tutorial videos will appear here once added.</p>
                    </div>
                  </div>
                </div>
                <?php
                  }
                ?>
              </div>
            </div>
            <?php include_once('../inc/footage.php'); ?>
          </div>
        </div>
      </div>
    </div>
  </body>
  <foot>
    <?php include_once('../inc/footer.php'); ?>
    <style>
      .tutorial-grid .card {
        transition: transform 0.2s, box-shadow 0.2s;
      }
      .tutorial-grid .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      }
      .avatar-md {
        width: 3.5rem;
        height: 3.5rem;
      }
    </style>
  </foot>
</html>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Help");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active"); 
      };
    </script>
 