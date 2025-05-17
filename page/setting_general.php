<?php
  $page = 'Help & Support';
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
      $page = 'Help & Support';
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

              <!-- Content -->
              <div class="row">
                <!-- Contact Support Card -->
                <div class="col-12 col-lg-4 mb-4">
                  <div class="card h-100">
                    <div class="card-header">
                      <h5 class="card-title mb-0">Contact Support</h5>
                    </div>
                    <div class="card-body">
                      <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                          <i class='bx bx-phone me-2'></i>
                          <h6 class="mb-0">Phone Support</h6>
                        </div>
                        <p class="ms-4">+855 12 345 678</p>
                      </div>
                      <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                          <i class='bx bx-envelope me-2'></i>
                          <h6 class="mb-0">Email Support</h6>
                        </div>
                        <p class="ms-4">support@asiadental.com</p>
                      </div>
                      <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                          <i class='bx bx-time-five me-2'></i>
                          <h6 class="mb-0">Support Hours</h6>
                        </div>
                        <p class="ms-4">Monday - Friday: 8:00 AM - 5:00 PM</p>
                        <p class="ms-4">Saturday: 8:00 AM - 12:00 PM</p>
                        <p class="ms-4">Sunday: Closed</p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Video Tutorials Card -->
                <div class="col-12 col-lg-8 mb-4">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="card-title mb-0">Tutorial Videos</h5>
                      <button type="button" id="addVideoModal" class="btn btn-primary btn-sm" >
                        <i class='bx bx-plus me-1'></i> Add Video
                      </button>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-hover" id="videoTable">
                          <thead>
                            <tr>
                              <th>Title</th>
                              <th>Category</th>
                              <th>Date Added</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <!-- Video data will be loaded here via JavaScript -->
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Video Player Section -->
              <div class="row">
                <div class="col-12">
                  <div class="card mb-4">
                    <div class="card-header">
                      <h5 class="card-title mb-0" id="currentVideoTitle">Featured Tutorial</h5>
                    </div>
                    <div class="card-body">
                      <div class="ratio ratio-16x9" id="videoPlayer">
                        <!-- Default video or placeholder -->
                        <iframe src="https://www.youtube.com/embed/oV4twpTcHvo" 
                                title="Dental Practice Management Software Training" 
                                allowfullscreen></iframe>
                      </div>
                      <div class="mt-3">
                        <h6 id="videoDescription">Dental Practice Management Software Training - Learn how to properly use the system features</h6>
                        <p class="text-muted" id="videoCategory">Category: General Training</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Add/Edit Video Modal -->
              <!-- <div class="modal fade" id="addVideoModal" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog">
                  <form class="modal-content" id="videoForm"> 
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalTitle">Add Tutorial Video</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> -->
                      
                <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 50%;" tabindex="-1" id="tutorialOffcanvas" 
                  aria-labelledby="tutorialOffcanvasLabel"> 
                  <div class="offcanvas-header">
                      <h5 class="offcanvas-title" id="tutorialOffcanvasLabel">Add Tutorial Video</h5>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                  <form  id="addForm" method="POST" enctype="multipart/form-data"> 
                      <input type="hidden" id="videoId" name="videoId">
                      <div class="mb-3">
                        <label for="videoTitle" class="form-label">Video Title</label>
                        <input type="text" class="form-control" id="videoTitle" name="videoTitle" required>
                      </div>
                      <div class="mb-3">
                        <label for="videoLink" class="form-label">YouTube Video Link</label>
                        <input type="url" class="form-control" id="videoLink" name="videoLink" 
                               placeholder="https://www.youtube.com/watch?v=..." required>
                        <small class="text-muted">Paste the full YouTube video URL</small>
                      </div>
                      <div class="mb-3">
                        <label for="videoCategory" class="form-label">Category</label>
                        <select class="form-select" id="videoCategory" name="videoCategory" required>
                          <option value="">Select category</option>
                          <option value="General Training">General Training</option>
                          <option value="Patient Management">Patient Management</option>
                          <option value="Appointment Scheduling">Appointment Scheduling</option>
                          <option value="Billing & Reports">Billing & Reports</option>
                          <option value="System Settings">System Settings</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="videoDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="videoDescription" name="videoDescription" rows="3"></textarea>
                      </div>
                      
                      
                      <div class="offcanvas-footer" style="margin-bottom:50px;margin-top:50px;">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form> 
                </div>
              </div> 
                    <!-- </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form>
                </div>
              </div> -->

              <!-- Delete Confirmation Modal -->
              <div class="modal fade" id="deleteModal" tabindex="-1">
                <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Delete Video</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p>Are you sure you want to delete this tutorial video?</p>
                      <input type="hidden" id="deleteVideoId">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                    </div>
                  </div>
                </div>
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
    <script src="../script/help-tutorials.js"></script>
  </foot>
</html>
<script type="text/javascript">
  window.onload = function(){
    var activeMenu = document.getElementById("Setting");
    activeMenu.classList.add("open");
    activeMenu.classList.add("active");
    var activeSubMenu = document.getElementById("sub-General Setting");
    activeSubMenu.classList.add("active");
  };
  $(document).on('click', '#addVideoModal', function() {
    // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('tutorialOffcanvas'));
    offcanvas.show(); 
  });
</script>