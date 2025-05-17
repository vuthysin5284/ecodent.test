<?php
  $page = 'Video Management';
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
      $page = 'Video Management';
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
                    <span class="text-muted fw-light">Support /</span> Video Management
                  </h4>
                </div>
                <div class="py-3">
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#videoModal">
                    <i class="bx bx-plus me-1"></i> Add New Video
                  </button>
                </div>
              </div>

              <!-- Video Table -->
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title mb-0">Manage Tutorial Videos</h5>
                </div>
                <div class="table-responsive text-nowrap">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        // Get videos from database
                        $sql = "SELECT * FROM tutorial_videos ORDER BY date_added DESC";
                        $result = $CON->query($sql);
                        
                        if ($result->num_rows > 0) {
                          while($row = $result->fetch_assoc()) {
                      ?>
                      <tr>
                        <td>
                          <strong><?php echo $row['title']; ?></strong>
                        </td>
                        <td><?php echo $row['category']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['date_added'])); ?></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="javascript:void(0);" onclick="editVideo(<?php echo $row['id']; ?>)">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                              </a>
                              <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteVideo(<?php echo $row['id']; ?>)">
                                <i class="bx bx-trash me-1"></i> Delete
                              </a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <?php
                          }
                        } else {
                      ?>
                      <tr>
                        <td colspan="4" class="text-center py-3">No tutorial videos found</td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Add/Edit Video Modal -->
              <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalTitle">Add New Tutorial Video</h5>
                      <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                      ></button>
                    </div>
                    <form id="videoForm" action="../api/v1/clinic/tutorial-video.php" method="post">
                      <div class="modal-body">
                        <input type="hidden" id="video_id" name="video_id" value="">
                        <input type="hidden" id="action" name="action" value="add">
                        
                        <div class="row">
                          <div class="col mb-3">
                            <label for="title" class="form-label">Video Title</label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="Enter title" required />
                          </div>
                        </div>
                        <div class="row">
                          <div class="col mb-3">
                            <label for="video_link" class="form-label">YouTube Video URL</label>
                            <input type="url" id="video_link" name="video_link" class="form-control" placeholder="https://www.youtube.com/watch?v=..." required />
                            <small class="text-muted">Paste the full YouTube video URL</small>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select id="category" name="category" class="form-select" required>
                              <option value="">Select a category</option>
                              <option value="Getting Started">Getting Started</option>
                              <option value="Patient Management">Patient Management</option>
                              <option value="Appointment Scheduling">Appointment Scheduling</option>
                              <option value="Billing & Payments">Billing & Payments</option>
                              <option value="Reports & Analytics">Reports & Analytics</option>
                              <option value="System Settings">System Settings</option>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter video description"></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                          Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Delete Confirmation Modal -->
              <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Delete Tutorial Video</h5>
                      <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                      ></button>
                    </div>
                    <div class="modal-body">
                      <p>Are you sure you want to delete this tutorial video? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                      </button>
                      <form id="deleteForm" action="../api/v1/clinic/tutorial-video.php" method="post">
                        <input type="hidden" id="delete_video_id" name="video_id" value="">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-danger">Delete</button>
                      </form>
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
    
    <script>
      // Function to open edit modal with video data
      function editVideo(videoId) {
        // Fetch video data using AJAX
        fetch('../api/v1/clinic/tutorial-video.php?action=get&video_id=' + videoId)
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Populate the form with video data
              document.getElementById('video_id').value = data.video.id;
              document.getElementById('title').value = data.video.title;
              document.getElementById('video_link').value = data.video.video_link;
              document.getElementById('category').value = data.video.category;
              document.getElementById('description').value = data.video.description;
              
              // Change modal title and action
              document.getElementById('modalTitle').innerText = 'Edit Tutorial Video';
              document.getElementById('action').value = 'edit';
              
              // Show the modal
              var videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
              videoModal.show();
            } else {
              alert('Error loading video data');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading video data');
          });
      }
      
      // Function to open delete confirmation modal
      function deleteVideo(videoId) {
        document.getElementById('delete_video_id').value = videoId;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
      }
    </script>
  </body>
  <foot>
    <?php include_once('../inc/footer.php'); ?>
  </foot>
</html>

    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Setting");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("sub-Category");
        activeSubMenu.classList.add("active");
      };
    </script>