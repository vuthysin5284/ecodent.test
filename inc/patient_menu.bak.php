  
              <div class="card mb-3">
								<div class="card-body p-2">
									<div class="d-flex gap-2 justify-content-between align-items-center">
										<!-- <div class="row justify-content-between"> -->
											<a href="../page/patient_personal.php?pgid=<?=$pid;?>&cid=<?=$cid;?>&apid=<?=$apid;?>" class="d-block btn btn-text-primary">
												<?php 
													$CUST = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM `tbl_customer` WHERE `cust_code` = '".$_GET['pid']."' LIMIT 1"));
													$id = $CUST['id'];
													$code = $CUST['cust_code'];
													$fname = $CUST['cust_fname'];
													$age = (date('Y') - date('Y', strtotime($CUST['cust_dob'])));
													$custId = 'P-'. sprintf('%05d', $CUST['id']);
													$folder = ($CUST['cust_image'] == '0') ? '' : $custId.'/';
													$image = $CUST['cust_image'];
													$gender = ($CUST['cust_gender'] == 0) ? 'Female' : 'Male';
												?>
												<div class="d-flex align-items-center gap-2">
													<img src="../images/profiles/<?=$folder?><?=$image?>.jpg" alt="Avatar" class="rounded-circle" width="40" height="40">
													<div>
														<div class="fw-bold" id="patientName"><?=$fname?></div>
														<div class="text-muted small" id="patientDetails">
															<span id="patientGender"><?=$gender?></span> &bull; <span id="patientAge"><?=$age?></span> 
														</div>
													</div>
												</div>
											</a>
											<div class="d-flex gap-2 justify-content-center align-items-center">
											<a href="patient_history.php?pgid=<?=$_GET['pgid']?>" class="d-block btn btn-label-primary">
												<i class="tf-icons bx bx-plus-medical"></i>
												<div class="text-sm">Medical</div>
											</a>
											<a href="patient_chart.php?pgid=<?=$_GET['pgid']?>" class="d-block btn btn-label-primary">
												<img src="../assets/svg/icons/teeth-open.svg" alt="Chart Icon" width="20" height="20" class="chart-icon">
												<style>
												.chart-icon {
													transition: filter 0.2s;
												}
												a.btn-label-primary.active .chart-icon,
												a.btn-label-primary:hover .chart-icon {
													filter: brightness(0) invert(1);
												}
												</style>
												<div class="text-sm">Chart</div>
											</a>
											<a href="patient_invoice.php?pgid=<?=$_GET['pgid']?>" class="d-block btn btn-label-primary">
												<i class="tf-icons bx bx-file"></i>
												<div class="text-sm">Invoices</div>
											</a>
											<a href="patient_payment.php?pgid=<?=$_GET['pgid']?>" class="d-block btn btn-label-primary">
												<i class="tf-icons bx bx-credit-card"></i>
												<div class="text-sm">Payments</div>
											</a>
											<a href="patient_xray.php?pgid=<?=$_GET['pgid']?>" class="d-block btn btn-label-primary">
												<i class="tf-icons bx bx-image"></i>
												<div class="text-sm">X-Ray</div>
											</a>
											<a href="patient_files.php?pgid=<?=$_GET['pgid']?>" class="d-block btn btn-label-primary">
												<i class="tf-icons bx bx-image"></i>
												<div class="text-sm">Gallery</div>
											</a>
											<a href="patient_files.php?pgid=<?=$_GET['pgid']?>" class="d-block btn btn-label-primary">
												<i class="tf-icons bx bx-file"></i>
												<div class="text-sm">Files</div>
											</a>
											<a href="patient_clinical_note.php?pgid=<?=$_GET['pgid']?>" class="d-block btn btn-label-primary">
												<i class="tf-icons bx bx-cog"></i>
												<div class="text-sm">Notes</div>
											</a>
											<a href="patient_prescription.php?pgid=<?=$_GET['pgid']?>" class="d-block btn btn-label-primary">
												<i class="tf-icons bx bx-capsule"></i>
												<div class="text-sm">Prescription</div>
											</a>
											<a href="patient_activity.php?pgid=<?=$_GET['pgid']?>" class="d-block btn btn-label-primary">
												<i class="tf-icons bx bx-time"></i>
												<div class="text-sm">Activity</div>
											</a>
										</div>
									</div>
								</div>
							</div>
<!-- 
<div class="patient-menu row" style="margin:auto;padding:10px">
  <div class="btn-group" role="group" aria-label="First group">
    <a id="patient-personal" type="button" class="btn btn-primary" href="../page/patient_personal.php?pgid=<?=$pid;?>&cid=<?=$cid;?>&apid=<?=$apid;?>">
      <span class="d-sm-none d-none d-md-block">Personal</span>
      <i class="bx bx-calendar d-sm-block d-block d-md-none"></i>
    </a>
    <a id="patient-appointment" type="button" class="btn btn-primary" href="../page/patient_appointment.php?pgid=<?=$pid;?>&cid=<?=$cid;?>&apid=<?=$apid;?>">
      <span class="d-sm-none d-none d-md-block">Appointment</span>
      <i class="bx bx-calendar d-sm-block d-block d-md-none"></i>
    </a>
    <a id="patient-history" type="button" class="btn btn-primary" href="../page/patient_history.php?pgid=<?=$pid;?>&cid=<?=$cid;?>&apid=<?=$apid;?>">
      <span class="d-sm-none d-none d-md-block">Patient History</span>
      <i class="bx bx-user d-sm-block d-block d-md-none"></i>
    </a>
    <a id="patient-treatment-plan" type="button" class="btn btn-primary" href="../page/patient_treatment_plan.php?pgid=<?=$pid;?>&cid=<?=$cid;?>&apid=<?=$apid;?>">
      <span class="d-sm-none d-none d-md-block">Treatment Plan</span>
      <i class="bx bx-grid-alt d-sm-block d-block d-md-none"></i>
    </a>
    <a id="notification-prescription" type="button" class="btn btn-primary" href="../page/notification_diagnosis.php?pgid=<?=$pid;?>&cid=<?=$cid;?>&apid=<?=$apid;?>">
      <span class="d-sm-none d-none d-md-block">Diagnosis</span>
      <i class="bx bx-grid-alt d-sm-block d-block d-md-none"></i>
    </a>
    <a id="patient-invoice" type="button" class="btn btn-primary" href="../page/patient_invoice.php?pgid=<?=$pid;?>&cid=<?=$cid;?>&apid=<?=$apid;?>">
      <span class="d-sm-none d-none d-md-block">Invoices</span>
      <i class='bx bx-credit-card d-sm-block d-block d-md-none'></i>
    </a>
    <a id="patient-clinical-note" type="button" class="btn btn-primary" href="../page/patient_clinical_note.php?pgid=<?=$pid;?>&cid=<?=$cid;?>&apid=<?=$apid;?>">
      <span class="d-sm-none d-none d-md-block">Clinical Notes</span>
      <i class="bx bx-note d-sm-block d-block d-md-none"></i>
    </a>
    <a id="patient-files" type="button" class="btn btn-primary" href="../page/patient_files.php?pgid=<?=$pid;?>&cid=<?=$cid;?>&apid=<?=$apid;?>">
      <span class="d-sm-none d-none d-md-block">Files</span>
      <i class="bx bx-image-alt d-sm-block d-block d-md-none"></i>
    </a>
    <a id="patient-files" type="button" class="btn btn-primary" href="../page/patient_files.php?pgid=<?=$pid;?>&cid=<?=$cid;?>&apid=<?=$apid;?>">
      <span class="d-sm-none d-none d-md-block">Depreciation</span>
      <i class="bx bx-image-alt d-sm-block d-block d-md-none"></i>
    </a>
  </div>
</div>  -->