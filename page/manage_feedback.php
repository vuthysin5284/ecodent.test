<?php
  $page = 'Feedback Management';
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
      $page = 'Feedback Management';
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
                    <span class="text-muted fw-light">Support /</span> Feedback Management
                  </h4>
                </div>
                <div class="py-3">
                  <button type="button" class="btn btn-primary" id="refreshFeedback">
                    <i class="bx bx-refresh me-1"></i> Refresh
                  </button>
                </div>
              </div>

              <!-- Feedback Statistics Cards -->
              <div class="row mb-4">
                <div class="col-md-3 mb-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="card-info">
                          <h5 class="mb-0">Total Feedback</h5>
                          <small class="text-muted">All time feedback count</small>
                        </div>
                        <div class="card-icon">
                          <span class="badge bg-label-primary rounded p-2">
                            <i class="bx bx-message-dots bx-sm"></i>
                          </span>
                        </div>
                      </div>
                      <h3 class="card-title mb-0 mt-2">
                        <?php
                          $sql = "SELECT COUNT(*) as total FROM feedback";
                          $result = $CON->query($sql);
                          $row = $result->fetch_assoc();
                          echo $row['total'];
                        ?>
                      </h3>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mb-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="card-info">
                          <h5 class="mb-0">Unread</h5>
                          <small class="text-muted">Pending feedback</small>
                        </div>
                        <div class="card-icon">
                          <span class="badge bg-label-warning rounded p-2">
                            <i class="bx bx-envelope-open bx-sm"></i>
                          </span>
                        </div>
                      </div>
                      <h3 class="card-title mb-0 mt-2">
                        <?php
                          $sql = "SELECT COUNT(*) as unread FROM feedback WHERE is_read = 0";
                          $result = $CON->query($sql);
                          $row = $result->fetch_assoc();
                          echo $row['unread'];
                        ?>
                      </h3>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mb-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="card-info">
                          <h5 class="mb-0">Bug Reports</h5>
                          <small class="text-muted">Issues reported by users</small>
                        </div>
                        <div class="card-icon">
                          <span class="badge bg-label-danger rounded p-2">
                            <i class="bx bx-bug bx-sm"></i>
                          </span>
                        </div>
                      </div>
                      <h3 class="card-title mb-0 mt-2">
                        <?php
                          $sql = "SELECT COUNT(*) as bugs FROM feedback WHERE feedback_type = 'Bug Report'";
                          $result = $CON->query($sql);
                          $row = $result->fetch_assoc();
                          echo $row['bugs'];
                        ?>
                      </h3>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mb-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="card-info">
                          <h5 class="mb-0">Feature Requests</h5>
                          <small class="text-muted">New ideas from users</small>
                        </div>
                        <div class="card-icon">
                          <span class="badge bg-label-success rounded p-2">
                            <i class="bx bx-bulb bx-sm"></i>
                          </span>
                        </div>
                      </div>
                      <h3 class="card-title mb-0 mt-2">
                        <?php
                          $sql = "SELECT COUNT(*) as features FROM feedback WHERE feedback_type = 'Feature Request'";
                          $result = $CON->query($sql);
                          $row = $result->fetch_assoc();
                          echo $row['features'];
                        ?>
                      </h3>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Feedback Table -->
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h5 class="mb-0">All Feedback</h5>
                  <div class="d-flex gap-2">
                    <div class="dropdown">
                      <button 
                        class="btn btn-outline-secondary dropdown-toggle" 
                        type="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                      >
                        <i class="bx bx-filter-alt me-1"></i>Filter
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="javascript:void(0);" data-filter="all">All Feedback</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" data-filter="unread">Unread</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" data-filter="bugreport">Bug Reports</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" data-filter="featurerequest">Feature Requests</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" data-filter="general">General Feedback</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="table-responsive text-nowrap">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Status</th>
                        <th>Subject</th>
                        <th>Type</th>
                        <th>Submitted By</th>
                        <th>Date Received</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        // Get feedback from database ordered by newest first
                        $sql = "SELECT 
                                  f.*, 
                                  u.username
                                FROM 
                                  feedback f 
                                LEFT JOIN 
                                  tbl_staff u ON f.user_id = u.id
                                ORDER BY 
                                  f.created_at DESC";
                                  
                        $result = $CON->query($sql);
                        
                        if ($result->num_rows > 0) {
                          while($row = $result->fetch_assoc()) {
                            // Define status badge
                            $statusBadge = $row['is_read'] == 0 ? 
                                          '<span class="badge bg-label-warning">Unread</span>' : 
                                          '<span class="badge bg-label-success">Read</span>';
                            
                            // Define type badge
                            $typeBadge = '';
                            switch ($row['feedback_type']) {
                              case 'Bug Report':
                                $typeBadge = '<span class="badge bg-danger">Bug Report</span>';
                                break;
                              case 'Feature Request':
                                $typeBadge = '<span class="badge bg-info">Feature Request</span>';
                                break;
                              default:
                                $typeBadge = '<span class="badge bg-secondary">General</span>';
                            }
                            
                            // Format date
                            $formattedDate = date('M d, Y h:i A', strtotime($row['created_at']));
                      ?>
                      <tr>
                        <td><?php echo $statusBadge; ?></td>
                        <td>
                          <strong><?php echo htmlspecialchars($row['subject']); ?></strong>
                        </td>
                        <td><?php echo $typeBadge; ?></td>
                        <td><?php echo htmlspecialchars($row['name']) . ' (' . htmlspecialchars($row['username'] ?: 'Guest') . ')'; ?></td>
                        <td><?php echo $formattedDate; ?></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="javascript:void(0);" 
                                 onclick="viewFeedback(<?php echo $row['id']; ?>)">
                                <i class="bx bx-show-alt me-1"></i> View
                              </a>
                              <a class="dropdown-item" href="javascript:void(0);"
                                 onclick="markAsRead(<?php echo $row['id']; ?>)">
                                <i class="bx bx-envelope-open me-1"></i> Mark as Read
                              </a>
                              <a class="dropdown-item" href="mailto:<?php echo htmlspecialchars($row['email']); ?>?subject=Re: <?php echo htmlspecialchars($row['subject']); ?>">
                                <i class="bx bx-reply me-1"></i> Reply via Email
                              </a>
                              <a class="dropdown-item text-danger" href="javascript:void(0);"
                                 onclick="deleteFeedback(<?php echo $row['id']; ?>)">
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
                        <td colspan="6" class="text-center py-3">No feedback received yet</td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- View Feedback Modal -->
              <div class="modal fade" id="viewFeedbackModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="feedbackSubject">Feedback Details</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row mb-3">
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="form-label">Sender Name</label>
                            <p class="form-control-static" id="feedbackName">-</p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <p class="form-control-static" id="feedbackEmail">-</p>
                          </div>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="form-label">Feedback Type</label>
                            <p class="form-control-static" id="feedbackType">-</p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="form-label">Date Submitted</label>
                            <p class="form-control-static" id="feedbackDate">-</p>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Message</label>
                        <div class="p-3 bg-light rounded" id="feedbackMessage">-</div>
                      </div>
                      <div id="userDetails" class="mt-4 pt-3 border-top">
                        <h6>User Information</h6>
                        <div class="row">
                          <div class="col-md-4">
                            <p class="mb-1"><small class="text-muted">Username:</small></p>
                            <p id="feedbackUsername">-</p>
                          </div>
                          <div class="col-md-4">
                            <p class="mb-1"><small class="text-muted">User ID:</small></p>
                            <p id="feedbackUserId">-</p>
                          </div>
                          <div class="col-md-4">
                            <p class="mb-1"><small class="text-muted">User Role:</small></p>
                            <p id="feedbackUserRole">-</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <a href="#" class="btn btn-primary" id="replyEmailBtn">
                        <i class="bx bx-reply me-1"></i> Reply via Email
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Delete Confirmation Modal -->
              <div class="modal fade" id="deleteFeedbackModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Delete Feedback</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p>Are you sure you want to delete this feedback? This action cannot be undone.</p>
                      <input type="hidden" id="deleteFeedbackId" value="">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
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
      // Function to view feedback details
      function viewFeedback(feedbackId) {
        // Fetch feedback data using AJAX
        fetch('../api/v1/clinic/feedback-actions.php?action=get&id=' + feedbackId)
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const feedback = data.feedback;
              
              // Populate modal with feedback data
              document.getElementById('feedbackSubject').textContent = feedback.subject;
              document.getElementById('feedbackName').textContent = feedback.name || '-';
              document.getElementById('feedbackEmail').textContent = feedback.email || '-';
              document.getElementById('feedbackType').textContent = feedback.feedback_type;
              document.getElementById('feedbackDate').textContent = feedback.created_at;
              document.getElementById('feedbackMessage').textContent = feedback.message;
              document.getElementById('feedbackUsername').textContent = feedback.username || '-';
              document.getElementById('feedbackUserId').textContent = feedback.user_id || '-';
              document.getElementById('feedbackUserRole').textContent = feedback.user_role || '-';
              
              // Set up reply email button
              document.getElementById('replyEmailBtn').href = 'mailto:' + feedback.email + '?subject=Re: ' + feedback.subject;
              
              // Mark as read
              markAsRead(feedbackId, true);
              
              // Show modal
              const modal = new bootstrap.Modal(document.getElementById('viewFeedbackModal'));
              modal.show();
            } else {
              alert('Error: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching feedback details');
          });
      }
      
      // Function to mark feedback as read
      function markAsRead(feedbackId, silent = false) {
        fetch('../api/v1/clinic/feedback-actions.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'action=read&id=' + feedbackId
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            if (!silent) {
              alert('Feedback marked as read');
              location.reload();
            }
          } else {
            if (!silent) {
              alert('Error: ' + data.message);
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          if (!silent) {
            alert('An error occurred');
          }
        });
      }
      
      // Function to delete feedback
      function deleteFeedback(feedbackId) {
        document.getElementById('deleteFeedbackId').value = feedbackId;
        const modal = new bootstrap.Modal(document.getElementById('deleteFeedbackModal'));
        modal.show();
      }
      
      // Setup event listeners when document is loaded
      document.addEventListener('DOMContentLoaded', function() {
        // Set up refresh button
        document.getElementById('refreshFeedback').addEventListener('click', function() {
          location.reload();
        });
        
        // Set up filter dropdown
        document.querySelectorAll('[data-filter]').forEach(item => {
          item.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            window.location.href = 'feedback-management.php?filter=' + filter;
          });
        });
        
        // Set up delete confirmation button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
          const feedbackId = document.getElementById('deleteFeedbackId').value;
          
          fetch('../api/v1/clinic/feedback-actions.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=delete&id=' + feedbackId
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Feedback deleted successfully');
              location.reload();
            } else {
              alert('Error: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
          });
          
          // Close the modal
          const modal = bootstrap.Modal.getInstance(document.getElementById('deleteFeedbackModal'));
          modal.hide();
        });
      });
    </script>
  </body>
  <foot>
    <?php include_once('../inc/footer.php'); ?>
  </foot>
</html>

    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Send Feedback");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active"); 
      };
    </script>