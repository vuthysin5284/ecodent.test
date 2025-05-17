<div class="card mb-3">
	<div class="card-body p-2">
		<div class="d-flex gap-2 justify-content-between align-items-center"> 
			<span class="d-block btn btn-text-primary">
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
			</span>
			<div class="d-flex gap-2 justify-content-center align-items-center">
			<a href="patient_history.php?pgid=<?=$_GET['pgid']?>&pid=<?=$_GET['pid']?>&cid=<?=$_GET['pid']?>&apid=<?=$_GET['apid']?>" 
				id="patient-medical" class="d-block btn btn-label-primary">
				<i class="tf-icons bx bx-plus-medical"></i>
				<div class="text-sm">Medical</div>
			</a>
			<a href="patient_chart.php?pgid=<?=$_GET['pgid']?>&pid=<?=$_GET['pid']?>&cid=<?=$_GET['pid']?>&apid=<?=$_GET['apid']?>" 
				id="patient-chart" class="d-block btn btn-label-primary">
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
			</a><!--patient_invoice-->
			<a href="patient_invoice.php?pgid=<?=$_GET['pgid']?>&pid=<?=$_GET['pid']?>&cid=<?=$_GET['pid']?>&apid=<?=$_GET['apid']?>" 
				id="patient-invoice" class="d-block btn btn-label-primary">
				<i class="tf-icons bx bx-file"></i>
				<div class="text-sm">Invoices</div>
			</a>
			<a href="patient_payment.php?pgid=<?=$_GET['pgid']?>&pid=<?=$_GET['pid']?>&cid=<?=$_GET['pid']?>&apid=<?=$_GET['apid']?>" 
				id="patient-payment" class="d-block btn btn-label-primary">
				<i class="tf-icons bx bx-credit-card"></i>
				<div class="text-sm">Payments</div>
			</a>
			<a href="patient_xray.php?pgid=<?=$_GET['pgid']?>&pid=<?=$_GET['pid']?>&cid=<?=$_GET['pid']?>&apid=<?=$_GET['apid']?>" 
				id="patient-xray" class="d-block btn btn-label-primary">
				<i class="tf-icons bx bx-image"></i>
				<div class="text-sm">X-Ray</div>
			</a>
			<a href="patient_gallary.php?pgid=<?=$_GET['pgid']?>&pid=<?=$_GET['pid']?>&cid=<?=$_GET['pid']?>&apid=<?=$_GET['apid']?>" 
				id="patient-gallary" class="d-block btn btn-label-primary">
				<i class="tf-icons bx bx-image"></i>
				<div class="text-sm">Gallery</div>
			</a>
			<a href="patient_files.php?pgid=<?=$_GET['pgid']?>&pid=<?=$_GET['pid']?>&cid=<?=$_GET['pid']?>&apid=<?=$_GET['apid']?>" 
				id="patient-files" class="d-block btn btn-label-primary">
				<i class="tf-icons bx bx-file"></i>
				<div class="text-sm">Files</div>
			</a>
			<a href="patient_clinical_note.php?pgid=<?=$_GET['pgid']?>&pid=<?=$_GET['pid']?>&cid=<?=$_GET['pid']?>" 
				id="patient-notes" class="d-block btn btn-label-primary">
				<i class="tf-icons bx bx-cog"></i>
				<div class="text-sm">Notes</div>
			</a>
			<a href="patient_prescription.php?pgid=<?=$_GET['pgid']?>&pid=<?=$_GET['pid']?>&cid=<?=$_GET['pid']?>&apid=<?=$_GET['apid']?>" 
				id="patient-prescription" class="d-block btn btn-label-primary">
				<i class="tf-icons bx bx-capsule"></i>
				<div class="text-sm">Prescription</div>
			</a>
			<a href="patient_activity.php?pgid=<?=$_GET['pgid']?>&pid=<?=$_GET['pid']?>&cid=<?=$_GET['pid']?>&apid=<?=$_GET['apid']?>" 
				id="patient-activity" class="d-block btn btn-label-primary">
				<i class="tf-icons bx bx-time"></i>
				<div class="text-sm">Activity</div>
			</a>
		</div>
	</div>
</div>