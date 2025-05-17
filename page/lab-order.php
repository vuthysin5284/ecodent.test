<?php
  $page = 'Lab Order Management';
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
      $page = 'Lab Order Management';
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
                    <span class="text-muted fw-light">Laboratory /</span> Lab Orders
                  </h4>
                </div>
                <div class="py-3">
                  <button type="button" class="btn btn-primary" id="createLabOrderModal">
                    <i class="bx bx-plus me-1"></i> Create Lab Order
                  </button>
                </div>
              </div>

              <!-- Filter Card -->
              <div class="card mb-4">
                <div class="card-body">
                  <form id="filterForm" class="row g-3">
                    <div class="col-md-3 col-sm-6">
                      <label class="form-label" for="filterDateRange">Date Range</label>
                      <input type="text" class="form-control" id="filterDateRange" name="filterDateRange" placeholder="Select date range">
                    </div>
                    <div class="col-md-3 col-sm-6">
                      <label class="form-label" for="filterStatus">Status</label>
                      <select class="form-select" id="filterStatus" name="filterStatus">
                        <option value="">All Statuses</option>
                        <option value="new">New</option>
                        <option value="in_progress">In Progress</option>
                        <option value="pending_approval">Pending Approval</option>
                        <option value="ready">Ready for Pickup</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                      </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                      <label class="form-label" for="filterPriority">Priority</label>
                      <select class="form-select" id="filterPriority" name="filterPriority">
                        <option value="">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                      </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                      <label class="form-label" for="filterSearch">Search</label>
                      <div class="input-group">
                        <input type="text" class="form-control" id="filterSearch" name="filterSearch" placeholder="Order #, Patient, Doctor...">
                        <button type="submit" class="btn btn-primary"><i class="bx bx-search"></i></button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Lab Orders Table Card -->
              <div class="card">
                <h5 class="card-header">Lab Orders</h5>
                <div class="card-datatable table-responsive">
                  <table class="table border-top" id="labOrdersTable">
                    <thead>
                      <tr>
                        <th>Order #</th>
                        <th>Patient</th>
                        <th>Request Date</th>
                        <th>Due Date</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Lab orders will be loaded dynamically -->
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Create/Edit Lab Order Modal -->
              <!-- <div class="modal fade" id="createLabOrderModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalTitle">Create Lab Order</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="labOrderForm">
                      <div class="modal-body"> -->
              <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 50%;" tabindex="-1" id="laborderOffcanvas" 
                aria-labelledby="laborderOffcanvasLabel"> 
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="laborderOffcanvasLabel">Create Lab Order</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
				        <div class="offcanvas-body">
                  <form  id="labOrderForm" method="POST" enctype="multipart/form-data"> 
                        <input type="hidden" id="orderId" name="orderId">
                        
                        <!-- Order Information -->
                        <div class="mb-4">
                          <h6>Order Information</h6>
                          <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label" for="requestDate">Request Date</label>
                              <input type="date" id="requestDate" name="requestDate" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="dueDate">Due Date</label>
                              <input type="date" id="dueDate" name="dueDate" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="priority">Priority</label>
                              <select id="priority" name="priority" class="form-select" required>
                                <option value="">Select Priority</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                              </select>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="status">Status</label>
                              <select id="status" name="status" class="form-select" required>
                                <option value="new">New</option>
                                <option value="in_progress">In Progress</option>
                                <option value="pending_approval">Pending Approval</option>
                                <option value="ready">Ready for Pickup</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Patient Information -->
                        <div class="mb-4">
                          <h6>Patient Information</h6>
                          <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label" for="patientId">Patient</label>
                              <select id="patientId" name="patientId" class="form-select" required>
                                <option value="">Select Patient</option>
                                <?php
                                  $patientQuery = "SELECT id, cust_fname AS patient_name FROM tbl_customer limit 15 ORDER BY cust_fname";
                                  $patientResult = $CON->query($patientQuery);
                                  if ($patientResult->num_rows > 0) {
                                    while($row = $patientResult->fetch_assoc()) {
                                      echo '<option value="'.$row['id'].'">'.$row['patient_name'].'</option>';
                                    }
                                  }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Doctor Information -->
                        <div class="mb-4">
                          <h6>Doctor Information</h6>
                          <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label" for="doctorId">Doctor</label>
                              <select id="doctorId" name="doctorId" class="form-select" required>
                                <option value="">Select Doctor</option>
                                <?php
                                  $doctorQuery = "SELECT id, staff_fname AS doctor_name FROM tbl_staff limit 15 ORDER BY staff_fname";
                                  $doctorResult = $CON->query($doctorQuery);
                                  if ($doctorResult->num_rows > 0) {
                                    while($row = $doctorResult->fetch_assoc()) {
                                      echo '<option value="'.$row['id'].'">'.$row['doctor_name'].'</option>';
                                    }
                                  }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Lab Work Details -->
                        <div class="mb-4">
                          <h6>Lab Work Details</h6>
                          <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label" for="workType">Type of Work</label>
                              <select id="workType" name="workType" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="crown">Crown</option>
                                <option value="bridge">Bridge</option>
                                <option value="denture">Denture</option>
                                <option value="implant">Implant</option>
                                <option value="veneer">Veneer</option>
                                <option value="night_guard">Night Guard</option>
                                <option value="retainer">Retainer</option>
                                <option value="other">Other</option>
                              </select>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label" for="material">Material</label>
                              <select id="material" name="material" class="form-select" required>
                                <option value="">Select Material</option>
                                <option value="porcelain">Porcelain</option>
                                <option value="zirconia">Zirconia</option>
                                <option value="metal">Metal</option>
                                <option value="composite">Composite</option>
                                <option value="acrylic">Acrylic</option>
                                <option value="other">Other</option>
                              </select>
                            </div>
                            <div class="col-md-12">
                              <label class="form-label" for="shade">Shade</label>
                              <input type="text" id="shade" name="shade" class="form-control" placeholder="e.g., A1, A2, B1">
                            </div>
                          </div>
                        </div>
                        
                        <!-- Teeth Selection -->
                        <div class="mb-4">
                          <h6>Teeth Selection</h6>
                          <div class="tooth-chart mb-3 p-3">  
                            <div class="card-header">
                              <div class="mb-3">
                                <div class="btn-group" role="group" aria-label="Tooth Type Switch">
                                    <button type="button" id="adultTeethBtn" class="btn btn-primary active">Adult Teeth</button>
                                    <button type="button" id="childTeethBtn" class="btn btn-outline-primary">Child Teeth</button>
                                </div>
                              </div>
                            </div>
                            <!--  -->
                            <div id="divAdultTeeth">
                              <?php include_once('../modals/adult_teeth_modal.php'); ?> 
                            </div>
                            <div id="divChildTeeth" class="d-none">
                            <?php include_once('../modals/child_teeth_modal.php'); ?>
                            </div>

                            <!-- Teeth chart will be implemented here -->
                            <!-- <div class="row g-2 text-center">
                              <div class="col-12">
                                <small class="text-muted">Upper Right</small>
                              </div>
                              < ?php
                                for ($i = 18; $i >= 11; $i--) {
                                  echo '<div class="col-1"><div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="teeth[]" value="'.$i.'" id="tooth'.$i.'">
                                    <label class="form-check-label" for="tooth'.$i.'">'.$i.'</label>
                                  </div></div>';
                                }
                                for ($i = 21; $i <= 28; $i++) {
                                  echo '<div class="col-1"><div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="teeth[]" value="'.$i.'" id="tooth'.$i.'">
                                    <label class="form-check-label" for="tooth'.$i.'">'.$i.'</label>
                                  </div></div>';
                                }
                              ?>
                              <div class="col-12">
                                <small class="text-muted">Upper Left</small>
                              </div>
                            </div>
                            <div class="row g-2 text-center mt-3">
                              <div class="col-12">
                                <small class="text-muted">Lower Right</small>
                              </div>
                              < ?php
                                for ($i = 48; $i >= 41; $i--) {
                                  echo '<div class="col-1"><div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="teeth[]" value="'.$i.'" id="tooth'.$i.'">
                                    <label class="form-check-label" for="tooth'.$i.'">'.$i.'</label>
                                  </div></div>';
                                }
                                for ($i = 31; $i <= 38; $i++) {
                                  echo '<div class="col-1"><div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="teeth[]" value="'.$i.'" id="tooth'.$i.'">
                                    <label class="form-check-label" for="tooth'.$i.'">'.$i.'</label>
                                  </div></div>';
                                }
                              ?>
                              <div class="col-12">
                                <small class="text-muted">Lower Left</small>
                              </div>
                            </div>-->
                          </div>
                        </div> 
                        
                        <!-- Additional Instructions -->
                        <div class="mb-4">
                          <h6>Additional Instructions</h6>
                          <div class="row g-3">
                            <div class="col-md-12">
                              <textarea id="instructions" name="instructions" class="form-control" rows="3" placeholder="Enter any special instructions or notes for the lab..."></textarea>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Attachments -->
                        <div class="mb-4">
                          <h6>Attachments</h6>
                          <div class="row g-3">
                            <div class="col-md-12">
                              <input class="form-control" type="file" id="attachments" name="attachments[]" multiple>
                              <div class="form-text">Upload X-rays, photos, or digital scans</div>
                            </div>
                          </div>
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
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div> -->

              <!-- View Lab Order Modal -->
              <div class="modal fade" id="viewLabOrderModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Lab Order Details</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-lg-8">
                          <!-- Order Information -->
                          <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                              <h6 class="mb-0">Order Information</h6>
                              <span class="badge bg-label-primary" id="viewOrderNumber">LAB-0001</span>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <small class="text-muted">Request Date:</small>
                                <p id="viewRequestDate" class="mb-1">-</p>
                              </div>
                              <div class="col-md-6">
                                <small class="text-muted">Due Date:</small>
                                <p id="viewDueDate" class="mb-1">-</p>
                              </div>
                              <div class="col-md-6">
                                <small class="text-muted">Priority:</small>
                                <p id="viewPriority" class="mb-1">-</p>
                              </div>
                              <div class="col-md-6">
                                <small class="text-muted">Status:</small>
                                <p id="viewStatus" class="mb-1">-</p>
                              </div>
                            </div>
                          </div>
                          
                          <!-- Patient and Doctor Information -->
                          <div class="mb-4">
                            <h6 class="mb-2">Patient & Doctor</h6>
                            <div class="row">
                              <div class="col-md-6">
                                <small class="text-muted">Patient:</small>
                                <p id="viewPatient" class="mb-1">-</p>
                              </div>
                              <div class="col-md-6">
                                <small class="text-muted">Doctor:</small>
                                <p id="viewDoctor" class="mb-1">-</p>
                              </div>
                            </div>
                          </div>
                          
                          <!-- Lab Work Details -->
                          <div class="mb-4">
                            <h6 class="mb-2">Lab Work Details</h6>
                            <div class="row">
                              <div class="col-md-4">
                                <small class="text-muted">Type:</small>
                                <p id="viewWorkType" class="mb-1">-</p>
                              </div>
                              <div class="col-md-4">
                                <small class="text-muted">Material:</small>
                                <p id="viewMaterial" class="mb-1">-</p>
                              </div>
                              <div class="col-md-4">
                                <small class="text-muted">Shade:</small>
                                <p id="viewShade" class="mb-1">-</p>
                              </div>
                            </div>
                          </div>
                          
                          <!-- Selected Teeth -->
                          <div class="mb-4">
                            <h6 class="mb-2">Selected Teeth</h6>
                            <p id="viewTeeth" class="mb-1">-</p>
                          </div>
                          
                          <!-- Instructions -->
                          <div class="mb-4">
                            <h6 class="mb-2">Additional Instructions</h6>
                            <p id="viewInstructions" class="mb-1">-</p>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <!-- Status Timeline -->
                          <div class="card mb-4">
                            <div class="card-header">
                              <h6 class="mb-0">Order Timeline</h6>
                            </div>
                            <div class="card-body pt-1">
                              <ul class="timeline mb-0" id="viewTimeline">
                                <!-- Timeline will be populated dynamically -->
                              </ul>
                            </div>
                          </div>
                          
                          <!-- Attachments -->
                          <div class="card">
                            <div class="card-header">
                              <h6 class="mb-0">Attachments</h6>
                            </div>
                            <div class="card-body" id="viewAttachments">
                              <!-- Attachments will be populated dynamically -->
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" id="btnEditOrder">Edit Order</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Update Status Modal -->
              <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Update Order Status</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updateStatusForm">
                      <div class="modal-body">
                        <input type="hidden" id="statusOrderId" name="orderId">
                        <div class="mb-3">
                          <label class="form-label" for="newStatus">Status</label>
                          <select id="newStatus" name="newStatus" class="form-select" required>
                            <option value="new">New</option>
                            <option value="in_progress">In Progress</option>
                            <option value="pending_approval">Pending Approval</option>
                            <option value="ready">Ready for Pickup</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="statusNote">Note (Optional)</label>
                          <textarea id="statusNote" name="statusNote" class="form-control" rows="3" placeholder="Enter any notes about this status update..."></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Delete Confirmation Modal -->
              <div class="modal fade" id="deleteLabOrderModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Delete Lab Order</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" id="deleteOrderId">
                      <p>Are you sure you want to delete this lab order? This action cannot be undone.</p>
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
    <script src="../assets/vendor/libs/moment/moment.js"></script>
    <script src="../assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="../script/lab-orders.js"></script>
  </foot>
</html>
<script>
  
  // Switch button handlers
				$('#adultTeethBtn').on('click', function() {
						currentToothType = 'adult';
						$(this).addClass('active').removeClass('btn-outline-primary').addClass('btn-primary');
						$('#childTeethBtn').removeClass('active').removeClass('btn-primary').addClass('btn-outline-primary'); 
            
            $('#divAdultTeeth').removeClass('d-none'); 
            $('#divChildTeeth').addClass('d-none'); 
				});

				$('#childTeethBtn').on('click', function() {
						currentToothType = 'child';
						$(this).addClass('active').removeClass('btn-outline-primary').addClass('btn-primary');
						$('#adultTeethBtn').removeClass('active').removeClass('btn-primary').addClass('btn-outline-primary'); 

            $('#divAdultTeeth').addClass('d-none'); 
            $('#divChildTeeth').removeClass('d-none'); 
				});
</script>