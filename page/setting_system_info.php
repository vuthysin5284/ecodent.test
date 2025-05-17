<?php
  $page = 'System Info';
  // include_once('../inc/session.php');
  session_start();
  $log = $_SESSION['uid'];
  $clinic_id = $_SESSION['business_id'];
  $lang = 1;
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
      if ($lang == 1) {
        $page_category = 'Setting';
        $page_title = 'System Info';
        $text_btn_create = 'New';
        $text_form = 'System Info';
        $m1 = 'Method ID';
        $m2 = 'System Info';
        $th2 = 'Image';
        $th3 = 'System Info';
        $th4 = 'Action';
      } else {
        $page_category = 'ការកំណត់';
        $page_title = 'ព័ត៌មានរបស់ប្រព័ន្ធ';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'ព័ត៌មានរបស់ប្រព័ន្ធ';
        $m1 = 'លេខកូដ';
        $m2 = 'វិធីបង់ប្រាក់';
        $th2 = 'រូបភាព';
        $th3 = 'វិធីបង់ប្រាក់';
        $th4 = 'ដំណើរការ';
      }
      include_once('../inc/header.php');
      include_once('../inc/setting.php');

      // loading data
      $row = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM tbl_system_setting where id ='".$_SESSION["system_id"]."'")); 
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
 
              <div class="card mb-4">
                <div class="d-flex justify-content-between"> 
                    <?php include_once ('../inc/system_license_menu.php'); ?>  
                </div>
 
                <div class="card-body" style="margin:auto;width:80%"> 
                  <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <div class="row g-5">
                      <div class="col-md-5 col-lg-4 order-md-last">
                        <h4 class="d-flex justify-content-between align-items-center mb-3">
                          <span class="text-primary">Licence</span> 
                        </h4>
                        <ul class="list-group mb-3">
                          <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                              <h6 class="my-0 text-primary" style="font-weight: bold;"><?=$row["license_to"]?></h6>
                              <small class="text-body-secondary">Customer name</small>
                            </div> 
                          </li>
                          <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                              <h6 class="my-0">Balance amount (៛)</h6> 
                            </div> 
                            <span class="text-body-secondary text-primary" style="font-weight: bold;"><?=$row["balance_amount"]?></span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                              <small class="text-body-secondary">Joined Date</small>
                            </div> 
                              <h6 class="my-0 text-primary" style="font-weight: bold;"><?=$row["joined_date"]?></h6>
                          </li> 
                          <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div> 
                              <small class="text-body-secondary">Type</small>
                            </div> 
                            <span class="text-body-secondary text-primary" style="font-weight: bold;" ><?=$row["type"]?></span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between bg-body-tertiary">
                            <div>
                              <small class="text-body-secondary">Customer Code</small>
                            </div> 
                              <h6 class="my-0 text-primary" style="font-weight: bold;"><?=$row["licence_code"]?></h6> 
                          </li> 
                          <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                              <h6 class="my-0"><?=$row["recuring"]==1?'Daily':'Monthly'?></h6>
                              <small class="text-body-secondary">Recuring (៛)</small>
                            </div> 
                            <span class="text-body-secondary text-primary" style="font-weight: bold;"><?=$row["paid_amount"]?></span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                              <small class="text-body-secondary">Billing Invoice</small>
                            </div>  
                              <h6 class="my-0 text-primary" style="font-weight: bold;"><?=$row["billing_issue"]==1?'Auto':'Manual'?></h6>
                          </li>
                          <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                              <small class="text-body-secondary">Status</small>
                            </div> 
                              <h6 class="my-0 text-primary" style="font-weight: bold;"><?=$row["license_status"]==1?'Active':'Deactive'?></h6>
                          </li>
                          <li class="list-group-item d-flex justify-content-between bg-body-tertiary">
                            <div>
                              <h6 class="my-0"><?=$row["address"]?></h6>
                              <small class="text-body-secondary">Shiping to</small>
                            </div> 
                          </li> 
                        </ul>
 
                      </div>
                      <div class="col-md-7 col-lg-8">
                        <form class="needs-validation" novalidate >
                          
                          <h4 class="mb-3">Personal </h4><ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                              <div class="row g-3">
                                <div class="col-sm-12">
                                  <label for="system_name_kh" class="form-label">First name </label>
                                  <input type="text" class="form-control" id="system_name_kh" placeholder="" value="<?=$row["system_name_kh"]?>" > 
                                </div>

                                <div class="col-sm-12">
                                  <label for="system_name_en" class="form-label">Last name</label>
                                  <input type="text" class="form-control" id="system_name_en" placeholder="" value="<?=$row["system_name_en"]?>" >
                                </div>
    

                                <div class="col-6">
                                  <label for="phone" class="form-label">Phone </label>
                                  <input type="phone" class="form-control" id="phone" value="<?=$row["phone"]?>"> 
                                </div>

                                <div class="col-6">
                                  <label for="email" class="form-label">Email </label>
                                  <input type="email" class="form-control" id="email" value="<?=$row["email"]?>"> 
                                </div> 
                              </div>
                            </li>
                          </ul>


                          <h4 class="mb-3" style="margin-top:50px">System Info </h4>
                          <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                              <div class="row">
                                <div class="col-sm-12">
                                  <label for="system_name_kh" class="form-label">System title Khmer </label>
                                  <input type="text" class="form-control" id="system_name_kh" placeholder="" value="<?=$row["system_name_kh"]?>" > 
                                </div>

                                <div class="col-sm-12">
                                  <label for="system_name_en" class="form-label">System title Latin</label>
                                  <input type="text" class="form-control" id="system_name_en" placeholder="" value="<?=$row["system_name_en"]?>" >
                                </div> 

                                <div class="col-12">
                                  <label for="clinic_address" class="form-label">Address </label>
                                  <input type="clinic_address" class="form-control" id="clinic_address" value="<?=$row["clinic_address"]?>"> 
                                </div>  
 
                              </div>
                            </li>
                          </ul>

                          <!-- billing address -->
                          <h4 class="mb-3" style="margin-top:50px">Invoice Info </h4> 
                          <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                              <div class="row g-3">
                                <div class="col-12">
                                  <label for="address" class="form-label">Invoice Address</label>
                                  <input type="text" class="form-control" id="address" value="<?=$row["address"]?>"> 
                                </div>  
                                <div class="col-12">
                                  <label for="remark_invoice" class="form-label">Remark on Invoice</label>
                                  <input type="text" class="form-control" id="remark_invoice"  value="<?=$row["remark_invoice"]?>"> 
                                </div> 
                                <div class="col-6">
                                  <label for="tax_no" class="form-label">Tax no.</label>
                                  <input type="text" class="form-control" id="tax_no" value="<?=$row["tax_no"]?>"> 
                                </div> 
                                <div class="col-12"> 
                                    <div class="col">
                                        <label for="files" class="form-label">Logo</label>
                                        <input class="form-control" type="file" id="files" name="files[]"/>
                                    </div> 
                                    <div class="row" id="imageData"></div> 
                                    <img src="../assets/img/icons/brands/<?=$row["logo"]?>" class="d-block w-60" style="margin:auto" alt="background-image">
                                  </div>
                              </div>
                            </li>
                          </ul>
 

                          <hr class="my-4">

                          <button class="w-100 btn btn-primary btn-lg"  type="submit">Save</button>
                        </form>
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
    <script src="../script/page_main.js"></script>
    <script src="../script/page_payment_method.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Setting");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("sub-System Information");
        activeSubMenu.classList.add("active"); 
      };
    </script>
  </foot>
</html>
