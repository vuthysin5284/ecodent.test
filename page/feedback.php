<?php
  $page = 'Send Feedback';
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
      $page = 'Send Feedback';
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
                    <span class="text-muted fw-light">Support /</span> Send Feedback
                  </h4>
                </div>
              </div>

              <!-- Content -->
              <div class="row">
                <div class="col-md-8">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Share Your Feedback</h5>
                    </div>
                    <div class="card-body">
                      <?php
                        if (isset($_SESSION['feedback_success'])) {
                          echo '<div class="alert alert-success alert-dismissible mb-3" role="alert">
                                  <div>' . $_SESSION['feedback_success'] . '</div>
                                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                          unset($_SESSION['feedback_success']);
                        }
                        
                        if (isset($_SESSION['feedback_error'])) {
                          echo '<div class="alert alert-danger alert-dismissible mb-3" role="alert">
                                  <div>' . $_SESSION['feedback_error'] . '</div>
                                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                          unset($_SESSION['feedback_error']);
                        }
                      ?>
                      
                      <form id="feedbackForm" action="../api/v1/clinic/feedback-process.php" method="post">
                        <div class="mb-3">
                          <label class="form-label" for="name">Your Name</label>
                          <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            placeholder="John Doe"
                            value="<?php echo isset($_SESSION['userData']['name']) ? $_SESSION['userData']['name'] : ''; ?>"
                            required
                          />
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="email">Your Email</label>
                          <div class="input-group input-group-merge">
                            <input
                              type="email"
                              id="email"
                              name="email"
                              class="form-control"
                              placeholder="john.doe@example.com"
                              value="<?php echo isset($_SESSION['userData']['email']) ? $_SESSION['userData']['email'] : ''; ?>"
                              required
                            />
                            <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                          </div>
                          <div class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="feedback_type">Feedback Type</label>
                          <select class="form-select" id="feedback_type" name="feedback_type" required>
                            <option value="">Select feedback type</option>
                            <option value="Bug Report">Bug Report</option>
                            <option value="Feature Request">Feature Request</option>
                            <option value="General Feedback">General Feedback</option>
                            <option value="Support Request">Support Request</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="subject">Subject</label>
                          <input
                            type="text"
                            class="form-control"
                            id="subject"
                            name="subject"
                            placeholder="Brief summary of your feedback"
                            required
                          />
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="message">Message</label>
                          <textarea
                            id="message"
                            name="message"
                            class="form-control"
                            placeholder="Tell us more about your feedback, ideas, or issues"
                            rows="5"
                            required
                          ></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Feedback</button>
                      </form>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-4">
                  <div class="card mb-4">
                    <div class="card-header">
                      <h5 class="mb-0">Contact Information</h5>
                    </div>
                    <div class="card-body">
                      <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                          <span class="badge bg-label-primary p-2 rounded">
                            <i class="bx bx-envelope bx-sm"></i>
                          </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                          <h6 class="mb-0">Email</h6>
                          <p class="mb-0 text-muted">support@asiadental.com</p>
                        </div>
                      </div>
                      <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                          <span class="badge bg-label-success p-2 rounded">
                            <i class="bx bx-phone bx-sm"></i>
                          </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                          <h6 class="mb-0">Phone</h6>
                          <p class="mb-0 text-muted">+855 12 345 678</p>
                        </div>
                      </div>
                      <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                          <span class="badge bg-label-info p-2 rounded">
                            <i class="bx bx-time-five bx-sm"></i>
                          </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                          <h6 class="mb-0">Support Hours</h6>
                          <p class="mb-0 text-muted">Monday - Friday: 8:00 AM - 5:00 PM</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="card mb-4">
                    <div class="card-header">
                      <h5 class="mb-0">FAQ</h5>
                    </div>
                    <div class="card-body">
                      <div class="mb-3">
                        <h6>How soon will I receive a response?</h6>
                        <p class="text-muted mb-0">We typically respond within 1-2 business days.</p>
                      </div>
                      <div class="mb-3">
                        <h6>Can I track my feedback?</h6>
                        <p class="text-muted mb-0">Yes, we'll send you a reference number to your email.</p>
                      </div>
                      <div>
                        <h6>What if my issue is urgent?</h6>
                        <p class="text-muted mb-0">For urgent matters, please call our support line directly.</p>
                      </div>
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
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('feedbackForm');
        
        form.addEventListener('submit', function(event) {
          const name = document.getElementById('name').value.trim();
          const email = document.getElementById('email').value.trim();
          const subject = document.getElementById('subject').value.trim();
          const message = document.getElementById('message').value.trim();
          const feedbackType = document.getElementById('feedback_type').value;
          
          let isValid = true;
          
          // Reset previous error messages
          document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
          document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
          
          // Validate name
          if (name === '') {
            showError('name', 'Please enter your name');
            isValid = false;
          }
          
          // Validate email
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (email === '' || !emailRegex.test(email)) {
            showError('email', 'Please enter a valid email address');
            isValid = false;
          }
          
          // Validate feedback type
          if (feedbackType === '') {
            showError('feedback_type', 'Please select a feedback type');
            isValid = false;
          }
          
          // Validate subject
          if (subject === '') {
            showError('subject', 'Please enter a subject');
            isValid = false;
          }
          
          // Validate message
          if (message === '') {
            showError('message', 'Please enter your message');
            isValid = false;
          }
          
          if (!isValid) {
            event.preventDefault();
          }
        });
        
        function showError(fieldId, message) {
          const field = document.getElementById(fieldId);
          field.classList.add('is-invalid');
          
          const errorDiv = document.createElement('div');
          errorDiv.className = 'invalid-feedback';
          errorDiv.textContent = message;
          
          field.parentNode.appendChild(errorDiv);
        }
      });
    </script>
  </foot>
</html>

    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Send Feedback");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active"); 
      };
    </script>