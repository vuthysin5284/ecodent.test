<?php
  session_start();
  $page = 'Welcome';
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
              <div class="card">
                <div class="card-body p-0">
									<div id="carouselExampleAutoplaying" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="10000">
										<div class="carousel-inner">
											<div class="carousel-item active">
												<img src="../images/backgrounds/1.jpg" class="d-block w-100" alt="background-image">
											</div>
											<?php
												$SQL = "SELECT * FROM `tbl_background_image`";
												$QUERY = mysqli_query($CON, $SQL);
												while ($ROW = mysqli_fetch_assoc($QUERY)) {
													$img = $ROW['file_image'];
													echo '<div class="carousel-item">';
													echo '<img src="../images/backgrounds/'.$img.'" class="d-block w-100" alt="background-image">';
													echo '</div>';
												}
											?>
										</div>
									</div>
                  <?php if ($nav_staff_position_id == 1) { ?>
                  <div class="position-absolute top-0 end-0 mt-4 me-4 d-none" style="z-index : 1; ">
                    <button class="btn btn-icon btn-primary p-0" type="button" id="create" data-bs-toggle="modal" data-bs-target="#Modal">
                      <i class="bx bx-cog"></i>
                    </button>
                  </div>
                  <?php } ?>
                </div>
                <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                  <div class="modal-dialog">
                    <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                      <div class="modal-header">
                        <h5 class="modal-title" id="backDropModalTitle">Image Background</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row mb-3">
                          <div class="col">
                              <label for="files" class="form-label">Files</label>
                              <input class="form-control" type="file" id="files" name="files[]" multiple required/>
                          </div>
                        </div>
                        <div class="row" id="imageData"></div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                    </form>
                  </div>
                  <div class="position-fixed progress w-100 top-0 left-0">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
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
    <script src="../script/page_index.js"></script>
  </foot>
</html>
