<?php
  session_start();
  $page = 'Treatment Service';
  $log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
  if (!isset($log)) { header('Location: login.php'); }
  error_reporting(0);
  $cid = $_GET['cid']; 
  $tmpid = $_GET['tmpid'];
  $pgid = $_GET['pgid'];
  $is_invoice = $_GET['is_invoice']; 
  $apid = $_GET['apid']; 
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
        $page_category = 'Patients';
        $page_title = 'Treatment Service';
        $text_btn_create = 'Add Services';
        $text_form = 'Appointment Information';
        $text_form_item = 'Items Information';
        $text_form_description = '* Patient\'s ID, and Qr Code is auto generated!';
        $m1 = 'Treatment Services';
        $m2 = 'Tooth No';
        $m3 = 'Service Price ($)';
        $m4 = 'Quantity';
        $m5 = 'Discount (%)';
        $m6 = 'Total ($)';
        $th2 = 'Treatment Description';
        $th3 = 'Tooth No.';
        $th4 = 'Qty';
        $th5 = 'Price ($)';
        $th6 = 'Discount (%)';
        $th7 = 'Total ($)';
        $th8 = 'Action';
        $th9 = 'Sub Total :';
        $th10 = 'Discount :';
        $th11 = 'Grand Total :';
      } else {
        $page_category = 'អតិថិជន';
        $page_title = 'សេវាកម្មព្យាបាល';
        $text_btn_create = 'ជ្រើសរើសសេវាកម្ម';
        $text_form = 'បញ្ចូលពត៌មានការណាត់ជួប';
        $text_form_description = '* លេខកូដ និង Qr Code ត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ!';
        $m1 = 'សេវាកម្មព្យាបាល';
        $m2 = 'លេខធ្មេញ';
        $m3 = 'តម្លៃ ($)';
        $m4 = 'ចំនួនធ្មេញ';
        $m5 = 'បញ្ចុះតម្លៃ (%)';
        $m6 = 'សរុប ($)';
        $th2 = 'សេវាកម្មព្យាបាល';
        $th3 = 'លេខធ្មេញ';
        $th4 = 'ចំនួន';
        $th5 = 'តម្លៃ ($)';
        $th6 = 'បញ្ចុះតម្លៃ (%)';
        $th7 = 'សរុប ($)';
        $th8 = 'ដំណើរការ';
        $th9 = 'តម្លៃសរុប :';
        $th10 = 'បញ្ចុះតម្លៃ :';
        $th11 = 'សរុប :';
      }
      include_once('../inc/config.php');
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
  </head>
  <body>
    <input type="hidden" id="cid" name="cid" value="<?php echo $cid; ?>" class="form-control" required readonly/>
    <input type="hidden" id="tmpid" name="tmpid" value="<?php echo $tmpid; ?>" class="form-control" required readonly/>
    <input type="hidden" id="is_invoice" name="is_invoice" value="<?php echo $is_invoice; ?>" class="form-control" required readonly/>
    <?php
      $CUST = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM `tbl_customer` WHERE `cust_code` = '$cid' LIMIT 1"));
      $id = $CUST['id'];
      $code = $CUST['cust_code'];
      $fname = $CUST['cust_fname'];
      $age = (date('Y') - date('Y', strtotime($CUST['cust_dob'])));
      $custId = 'P-'. sprintf('%05d', $CUST['id']);
      $folder = ($CUST['cust_image'] == '0') ? '' : $custId.'/';
      $gender = ($CUST['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $image = $CUST['cust_image'];
      $contact = $CUST['cust_contact'];
      $address = $CUST['cust_address'];
      $image_path = '../images/profiles/'.$folder.''.$image.'.jpg';
    ?>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include_once('../inc/navigation_menu.php'); ?>
        <div class="layout-page">
          <?php include_once('../inc/navbar.php'); ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <!-- <div class="card mb-4">
                <div class="card-body">  
                  < ?php include_once ('../inc/patient_short_profile.php'); ?>
                </div>
              </div> -->
              <!--  -->
              <div class="card"> 
                <?php include_once ('../inc/patient_menu.php'); ?> 
                <div class="card-body">
                  <h4 class="fw-bold">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title.($is_invoice==1?' ( Issued Invoice )':' ( Processing )') ; ?>
                  </h4>
                </div>
                <div class="card-body" style="border:1px solid #EEE;margin: 10px;border-radius:15px">  
                  <!-- treatment step -->
                  <div class="card" style="box-shadow:none"> 
                    <!--  sub menu treatment step -->
                      
                    <div class="listing-tab col-md-12">
                      <?php include_once ('../inc/patient_treatment_step_menu.php'); ?> 
                      <div class="tab-content">
                        <!-- medical tab -->
                        <div role="tabpanel" class="tab-pane active" id="medicals"> 
                          <!-- action button -->
                          <div class="d-flex justify-content-between">
                            <div class="me-auto">
                              <!-- <h4 class="fw-bold py-3 mb-4">
                                <span class="text-muted fw-light">< ?php echo $page_category; ?> / </span>< ?php echo $page_title.($is_invoice==1?' ( Issued Invoice )':' ( Processing )') ; ?>
                              </h4> -->
                            
                            </div>
                            <div class="py-2">
                            <?php if($is_invoice==0){ ?>
                              <?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                                <button type="button" id="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal">
                                  <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; <?php echo $text_btn_create; ?></span>
                                    <i class="bx bx bx-plus d-block d-sm-none"></i>
                                </button>
                                <button type="button" id="createProduct" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalProduct">
                                  <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; Add Product</span>
                                    <i class="bx bx bx-plus d-block d-sm-none"></i>
                                </button>
                                <?php }?>
                              <?php }?>
                            </div> 
                          </div>  
                          <!-- service modal -->
                          <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                            <div class="modal-dialog">
                              <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                                <div class="modal-header">
                                  <h5 class="modal-title" id="backDropModalTitle"><?php echo $text_form_item; ?></h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <img src="../assets/img/elements/dentalchart.png" alt="img" class="d-block rounded mb-4" width="100%" />                        
                                  <div class="row mb-3">
                                    <?php echo select2Data($m1, 'service', 'SELECT * FROM `tbl_treatment_service` ORDER BY `service_description` ASC', 'service_description'); ?>
                                  </div>
                                  <div class="row mb-3">
                                    <?php echo select2Datas($m2, 'tooth', 'SELECT * FROM `tbl_tooth_item` ORDER BY `id` ASC', 'tooth_description'); ?>
                                  </div>
                                  <div class="row mb-3">
                                    <div class="col">
                                      <label for="id" class="form-label"><?php echo $m3; ?></label>
                                      <div class="input-group input-group-merge">
                                        <input type="text" class="form-control" id="price" name="price"/>
                                        <button type="button" class="input-group-text cursor-pointer" id="editPrice" value='0'><i id='editIcon'></i></button>
                                      </div>
                                    </div>
                                    <?php //echo inpText($m3, 'price', '0.00', '1'); ?>
                                    <?php echo inpText($m4, 'qty', '0', '2'); ?>
                                  </div>
                                  <div class="row mb-3">
                                    <?php echo inpText($m5, 'discount', ''); ?>
                                    <?php echo inpText($m6, 'total', '0.00', '2'); ?>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                              </form>
                            </div>
                          </div>
                          <!-- product modal -->
                          <div class="modal fade" id="ModalProduct" data-bs-backdrop="static" tabindex="-1">
                            <div class="modal-dialog">
                              <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                                <div class="modal-header">
                                  <h5 class="modal-title" id="backDropModalTitle"><?php echo $text_form_item; ?></h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <!-- <img src="../assets/img/elements/dentalchart.png" alt="img" class="d-block rounded mb-4" width="100%" />                         -->
                                  <div class="row mb-3">
                                    <?php echo select2Data("Item", 'product', 'SELECT * FROM `tbl_product` ORDER BY `prod_description` ASC', 'prod_description'); ?>
                                  </div>
                                  <!-- <div class="row mb-3">
                                    < ?php echo select2Datas($m2, 'tooth', 'SELECT * FROM `tbl_tooth_item` ORDER BY `id` ASC', 'tooth_description'); ?>
                                  </div> -->
                                  <div class="row mb-3">
                                    <div class="col">
                                      <label for="id" class="form-label"><?php echo "PRICE($)"; ?></label>
                                      <div class="input-group input-group-merge">
                                        <input type="text" class="form-control" id="price" name="price"/>
                                        <button type="button" class="input-group-text cursor-pointer" id="editPrice" value='0'><i id='editIcon'></i></button>
                                      </div>
                                    </div>
                                    <?php //echo inpText($m3, 'price', '0.00', '1'); ?>
                                    <?php echo inpText($m4, 'qty', '0', '2'); ?>
                                  </div>
                                  <div class="row mb-3">
                                    <?php echo inpText($m5, 'discount', ''); ?>
                                    <?php echo inpText($m6, 'total', '0.00', '2'); ?>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                              </form>
                            </div>
                          </div>
                          <!-- list -->
                          <div class="card-body">
                            <div class="table-responsive">
                              <form id="check" method="POST" enctype="multipart/form-data">
                                <table class="table table-bordered" id="dataTable">
                                  <thead class="text-nowrap">
                                    <tr>
                                      <th class="text-center"><input class="form-check-input" type="checkbox" id="checkAll" checked/></th>
                                      <th class="text-left w-100"><?php echo $th2; ?></th>
                                      <th class="text-center"><?php echo $th3; ?></th>
                                      <th class="text-center"><?php echo $th4; ?></th>
                                      <th class="text-center"><?php echo $th5; ?></th>
                                      <th class="text-center"><?php echo $th6; ?></th>
                                      <th class="text-center"><?php echo $th7; ?></th>
                                      <th class="text-center"><?php echo $th8; ?></th>
                                    </tr>
                                  </thead>
                                  <tbody class="text-nowrap"></tbody>
                                  <tfoot>
                                    <tr>
                                      <td colspan="6" style="text-align : right"><strong><?php echo $th9; ?></strong></td>
                                      <td colspan="2" class="p-1">
                                        <div class="input-group input-group-merge">
                                          <span class="input-group-text fw-bolder">$</span>
                                          <input type="text" class="form-control text-end bg-white fw-bolder" name="subTotal" id="subTotal" style="width: 10px" readonly/>
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="6" style="text-align : right"><strong><?php echo $th10; ?></strong></td>
                                      <td colspan="2" class="p-1">
                                        <div class="input-group input-group-merge">
                                          <input type="button" class="input-group-text fw-bolder cursor-pointer" id="toggleDisc" value ="%"/>
                                          <input type="text" class="form-control fw-bolder text-end" id="totalDiscount" name="totalDisc" value="0" style="width: 10px" />
                                        </div>
                                        <?php echo inpHidden('typeDisc', '2'); ?>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="6" style="text-align : right"><strong><?php echo $th11; ?></strong></td>
                                      <td colspan="2" class="p-1">
                                        <div class="input-group input-group-merge">
                                          <span class="input-group-text fw-bolder">$</span>
                                          <input type="text" class="form-control text-end bg-white fw-bolder" id="grandTotal" style="width: 10px" readonly/>
                                        </div>
                                      </td>
                                    </tr>
                                  </tfoot>
                                </table>
                                <!-- <button type="submit" class="btn btn-primary mt-2">Issue Quote</button>  -->
                                <button type="submit" id="btnGenerateInvoice" class="btn btn-primary mt-2 pull-right">Generate Invoices</button> 
                                <!-- <button type="submit" class="btn btn-primary mt-2">Create Invoice</button>  -->
                              </form>
                            </div>
                          </div> 
                        </div>

                        <!-- treatment tab -->
                        <div role="tabpanel" class="tab-pane" id="treating">
                          
                          <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">  
                            <?php if($is_invoice==0){ ?>
                              <?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                                <button type="button" id="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal">
                                  <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; Add Treatment</span>
                                    <i class="bx bx bx-plus d-block d-sm-none"></i>
                                </button> 
                                <?php }?>
                              <?php }?> 
                          </div>  
 
                          <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center"> 

                              <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                                  <img src="https://github.com/twbs.png" alt="" width="32" height="32" class="rounded-circle flex-shrink-0">
                                  <div class="d-flex gap-2 w-100 justify-content-between">
                                    <div>
                                      <h6 class="mb-0">List group item heading</h6>
                                      <p class="mb-0 opacity-75">Some placeholder content in a paragraph.</p>
                                    </div>
                                    <small class="opacity-50 text-nowrap">now</small>
                                  </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                                  <img src="https://github.com/twbs.png" alt="" width="32" height="32" class="rounded-circle flex-shrink-0">
                                  <div class="d-flex gap-2 w-100 justify-content-between">
                                    <div>
                                      <h6 class="mb-0">Another title here</h6>
                                      <p class="mb-0 opacity-75">Some placeholder content in a paragraph that goes a little longer so it wraps to a new line.</p>
                                    </div>
                                    <small class="opacity-50 text-nowrap">3d</small>
                                  </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                                  <img src="https://github.com/twbs.png" alt="" width="32" height="32" class="rounded-circle flex-shrink-0">
                                  <div class="d-flex gap-2 w-100 justify-content-between">
                                    <div>
                                      <h6 class="mb-0">Third heading</h6>
                                      <p class="mb-0 opacity-75">Some placeholder content in a paragraph.</p>
                                    </div>
                                    <small class="opacity-50 text-nowrap">1w</small>
                                  </div>
                                </a>
                              </div>
                          </div>

                        </div>

                        <!-- tools tab -->
                        <div role="tabpanel" class="tab-pane" id="tools"> 
                          <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">  
                            <?php if($is_invoice==0){ ?>
                              <?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                                <button type="button" id="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal">
                                  <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; Add Tools</span>
                                    <i class="bx bx bx-plus d-block d-sm-none"></i>
                                </button> 
                                <?php }?>
                              <?php }?>
                          </div>  
                          
                          <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
                            <div class="list-group">
                              <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                                <img src="https://github.com/twbs.png" alt="" width="32" height="32" class="rounded-circle flex-shrink-0">
                                <div class="d-flex gap-2 w-100 justify-content-between">
                                  <div>
                                    <h6 class="mb-0">List group item heading</h6>
                                    <p class="mb-0 opacity-75">Some placeholder content in a paragraph.</p>
                                  </div>
                                  <small class="opacity-50 text-nowrap">now</small>
                                </div>
                              </a>
                              <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                                <img src="https://github.com/twbs.png" alt="" width="32" height="32" class="rounded-circle flex-shrink-0">
                                <div class="d-flex gap-2 w-100 justify-content-between">
                                  <div>
                                    <h6 class="mb-0">Another title here</h6>
                                    <p class="mb-0 opacity-75">Some placeholder content in a paragraph that goes a little longer so it wraps to a new line.</p>
                                  </div>
                                  <small class="opacity-50 text-nowrap">3d</small>
                                </div>
                              </a>
                              <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                                <img src="https://github.com/twbs.png" alt="" width="32" height="32" class="rounded-circle flex-shrink-0">
                                <div class="d-flex gap-2 w-100 justify-content-between">
                                  <div>
                                    <h6 class="mb-0">Third heading</h6>
                                    <p class="mb-0 opacity-75">Some placeholder content in a paragraph.</p>
                                  </div>
                                  <small class="opacity-50 text-nowrap">1w</small>
                                </div>
                              </a>
                            </div>
                          </div>

                        </div>
                        <!-- assistants tab -->
                        <div role="tabpanel" class="tab-pane" id="assistants"> 
                          <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">  
                            <?php if($is_invoice==0){ ?>
                              <?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                                <button type="button" id="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal">
                                  <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; Add Assistant</span>
                                    <i class="bx bx bx-plus d-block d-sm-none"></i>
                                </button> 
                                <?php }?>
                              <?php }?> 
                          </div>  

                          <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
                            <div class="list-group">
                              <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                                <img src="https://github.com/twbs.png" alt="" width="32" height="32" class="rounded-circle flex-shrink-0">
                                <div class="d-flex gap-2 w-100 justify-content-between">
                                  <div>
                                    <h6 class="mb-0">List group item heading</h6>
                                    <p class="mb-0 opacity-75">Some placeholder content in a paragraph.</p>
                                  </div>
                                  <small class="opacity-50 text-nowrap">now</small>
                                </div>
                              </a>
                              <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                                <img src="https://github.com/twbs.png" alt="" width="32" height="32" class="rounded-circle flex-shrink-0">
                                <div class="d-flex gap-2 w-100 justify-content-between">
                                  <div>
                                    <h6 class="mb-0">Another title here</h6>
                                    <p class="mb-0 opacity-75">Some placeholder content in a paragraph that goes a little longer so it wraps to a new line.</p>
                                  </div>
                                  <small class="opacity-50 text-nowrap">3d</small>
                                </div>
                              </a>
                              <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                                <img src="https://github.com/twbs.png" alt="" width="32" height="32" class="rounded-circle flex-shrink-0">
                                <div class="d-flex gap-2 w-100 justify-content-between">
                                  <div>
                                    <h6 class="mb-0">Third heading</h6>
                                    <p class="mb-0 opacity-75">Some placeholder content in a paragraph.</p>
                                  </div>
                                  <small class="opacity-50 text-nowrap">1w</small>
                                </div>
                              </a>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div> <!-- end tab -->
                      

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
    <script src="../script/page_patient_treatment.js"></script>

   
    <script type="text/javascript">

      window.onload = function(){ 
        var activeMenu = document.getElementById("Patients");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("Patient List");
        activeSubMenu.classList.add("active");

        var activeTabMenu = document.getElementById("patient-treatment-plan");
        activeTabMenu.classList.add("active");

        var activeTab1Menu = document.getElementById("patient-treatment-medical");
        activeTab1Menu.classList.add("active");
      }; 


      $('#patient-treatment-menu a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
      });
    </script>
  </foot>
</html>

<style>   
  .listing-tab .tab-content ul li:hover{cursor:pointer;text-decoration:underline;}  
  .listing-tab { padding:0; }   
  .listing-tab .nav-tabs>li,.nav-tabs>li a:hover{margin-bottom:0;background:none;}
  .listing-tab .nav-tabs>li.active>a, .nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus{border:none;background:none;}
  .listing-tab .nav-tabs>li>a:hover{border-color:none;color:red;}
  .listing-tab .nav-tabs>li>a{border:0;color:#fff;width: 150px !important;border-radius:0px !important;}
  .listing-tab .nav-tabs>li.active>a{color:#fff;}
  .listing-tab {background-color:#fff;}
 
</style>