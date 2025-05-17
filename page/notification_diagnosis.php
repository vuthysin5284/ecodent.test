<?php
  $page = 'Diagnosis';
  include_once('../inc/session.php'); 
  $cid = $_GET['cid'];
  $pgid = $_GET['pgid']; 
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
      $page_category = 'Appointments';
      $page_title = 'Diagnosis';
      $text_btn_create = 'New Prescription';
      $text_form = 'Diagnosis';
      $text_form_description = '* Patient\'s ID, Image, and Qr Code is auto-fill when you select a patient!';
      $m1 = 'Remark';
      $th2 = 'Date & Time';
      $th3 = 'Remark';
      $th4 = 'Dentist';
      $th5 = 'Action';
    } else {
      $page_category = 'ការរំលឹក';
      $page_title = 'រោគវិនិច្ឆ័យ';
      $text_btn_create = 'បង្កើតថ្មី';
      $text_form = 'រោគវិនិច្ឆ័យ';
      $text_form_description = '* លេខកូដ រូបភាព និង Qr Code ត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ!';
      $m1 = 'សំគាល់';
      $th2 = 'ពេលវេលា';
      $th3 = 'សំគាល់';
      $th4 = 'ទន្តបណ្ឌិត';
      $th5 = 'ដំណើរការ';
    }
    include_once('../inc/config.php');
    include_once('../inc/header.php');
    include_once('../inc/setting.php');
    ?>  
  </head>
  <body> 
    <input type="hidden" id="cid" name="cid" value="<?php echo $cid; ?>" class="form-control" required readonly/>
    <input type="hidden" id="apid" name="apid" value="<?php echo $apid; ?>" class="form-control" required readonly/>
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

        // should be check patient can credit payment ***
        // 
      ?>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include_once('../inc/navigation_menu.php'); ?>
        <div class="layout-page">
          <?php include_once('../inc/navbar.php'); ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y"> 
              <!--  -->
              <!-- <div class="card mb-4">
                <div class="card-body">  
                  < ?php include_once ('../inc/patient_short_profile.php'); ?>
                </div>
              </div>      -->
              <div class="card">     
                <?php include_once ('../inc/patient_menu.php'); ?>
                <div class="card-body p-3"> 
                  <!--  -->
                  <div class="d-flex justify-content-between">
                    <div class="me-auto">
                      <h4 class="fw-bold py-3 mb-4">
                        <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                      </h4>
                    </div>
                    <div class="py-2">
                    <?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                      <button type="button" id="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal">
                        <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; <?php echo $text_btn_create; ?></span>
                        <i class="bx bx bx-plus d-block d-sm-none"></i>
                      </button>
                      <?php }?>
                    </div>
                  </div>
                  <!-- <div class="row">
                    <div class="d-flex h-100 col-lg-3 col-md-5 col-sm-12 col-12 mb-4">
                      <div class="input-group input-group-merge" id="reportrange">
                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                        <input type="text" id="date" class="form-control bg-white text-center" readonly />
                      </div>
                      <input type="hidden" id="str" class="form-control bg-white text-center" readonly />
                      <button type="button" class="btn btn-icon btn-primary mx-2" id="btnFilter"><i class="bx bx-filter-alt"></i></button>
                    </div>
                  </div> -->
                  <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                    <div class="modal-dialog" style="max-width: 95%;">
                      <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                        <div class="modal-header">
                          <h5 class="modal-title" id="backDropModalTitle">Form Prescription</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- body -->
                        <div class="modal-body"> 
                          <div class="row">
                            <div class="col-sm-3">  
                              <?php echo inpDate("Entry date", 'entry_date', ''); ?>
                              <?php echo inpDate("Post date", 'post_date', ''); ?> 
                              <?php echo inpTextarea("Remark", 'remark', ''); ?>  
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group" style="max-height: 500px; overflow-y: auto">
                                    <div class="col-sm-12">
                                        <table id="order_items-tbl" class="table table-striped table-bordered table-hover" width="100%">
                                            <thead>
                                            <tr style="background: #f7f9fa">
                                                <th style="width: 2%">#</th>
                                                <th style="width: 30%">Medicine</th>
                                                <th style="width: 5%; ">Instruction</th>
                                                <th style="width: 5%; text-align: center">Morning</th>
                                                <th style="width: 5%; text-align: center">Afternoon</th>
                                                <th style="width: 5%; text-align: center">Evening</th>
                                                <th style="width: 5%; text-align: center">Duration(Day)</th>
                                                <th style="width: 5%; text-align: center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr id='1'>
                                                <td style='padding: 3px;text-align:center'>1</td>
                                                <td style='padding: 3px;'><input type='text' class='form-control pull-left' name='medicine[]' /></td>
                                                <td style='padding: 3px;text-align: center'>
                                                  <select class="form-select" name='instruction[]'> 
                                                    <option value='1'>After Meal</option>
                                                    <option value='2'>Before Meal</option>
                                                  </select>
                                                </td>
                                                <td style='padding: 3px;'><input type='number' name='morning[]' min='0' value='1' style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name='afternoon[]' min='0' value='1'  style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name="evening[]" min='0' value='1' style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name="duration[]" min='0' value='1' style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;text-align:center'><button type="button" onClick='onDelete(1);' style="color: red">Delete</button></td>
                                            </tr>
                                            <tr id='2'>
                                                <td style='padding: 3px;text-align:center'>2</td>
                                                <td style='padding: 3px;'><input type='text' class='form-control pull-left' name='medicine[]' /></td>
                                                <td style='padding: 3px;text-align: center'>
                                                  <select class="form-select" name='instruction[]'> 
                                                    <option value='1'>After Meal</option>
                                                    <option value='2'>Before Meal</option>
                                                  </select>
                                                </td>
                                                <td style='padding: 3px;'><input type='number' name='morning[]' min='0' value='1'  style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name='afternoon[]' min='0' value='1'  style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name="evening[]" min='0' value='1' style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name="duration[]" min='0' value='2' style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;text-align:center'><button type="button" onClick='onDelete(2);' style="color: red">Delete</button></td>
                                            </tr>
                                            <tr id='3'>
                                                <td style='padding: 3px;text-align:center'>3</td>
                                                <td style='padding: 3px;'><input type='text' class='form-control pull-left' name='medicine[]' /></td>
                                                <td style='padding: 3px;text-align: center'>
                                                  <select class="form-select" name='instruction[]'> 
                                                    <option value='1'>After Meal</option>
                                                    <option value='2'>Before Meal</option>
                                                  </select>
                                                </td>
                                                <td style='padding: 3px;'><input type='number' name='morning[]' min='0' value='1'  style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name='afternoon[]' min='0' value='1'  style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name="evening[]" min='0' value='1'  style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name="duration[]" min='0' value='3'  style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;text-align:center'><button type="button" onClick='onDelete(3);' style="color: red">Delete</button></td>
                                            </tr>
                                            <tr id='4'>
                                                <td style='padding: 3px;text-align:center'>4</td>
                                                <td style='padding: 3px;'><input type='text' class='form-control pull-left' name='medicine[]' /></td>
                                                <td style='padding: 3px;text-align: center'>
                                                  <select class="form-select" id="instruction4" name='instruction[]'> 
                                                    <option value='1'>After Meal</option>
                                                    <option value='2'>Before Meal</option>
                                                  </select>
                                                </td>
                                                <td style='padding: 3px;'><input type='number' name='morning[]' min='0' value='1'  style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name='afternoon[]' min='0' value='1'  style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name="evening[]" min='0' value='1' style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name="duration[]" min='0' value='3' style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;text-align:center'><button type="button" onClick='onDelete(4);' style="color: red">Delete</button></td>
                                            </tr>
                                            <tr id='5'>
                                                <td style='padding: 3px;text-align:center'>5</td>
                                                <td style='padding: 3px;'><input type='text' class='form-control pull-left' name='medicine[]' /></td>
                                                <td style='padding: 3px;text-align: center'>
                                                  <select class="form-select" id="instruction5" name='instruction[]'> 
                                                    <option value='1'>After Meal</option>
                                                    <option value='2'>Before Meal</option>
                                                  </select>
                                                </td>
                                                <td style='padding: 3px;'><input type='number' name='morning[]' min='0' value='1'  style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name='afternoon[]' min='0' value='1'  style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name="evening[]" min='0' value='1' style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;'><input type='number' name="duration[]" min='0' value='3' style='text-align: center' class='form-control'/></td>
                                                <td style='padding: 3px;text-align:center'><button type="button" onClick='onDelete(5);' style="color: red">Delete</button></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <div class="col-sm-12">
                                        <button type="button" class="add-row pull-right">Add Row</button>
                                        <!-- <button type="button" class="delete-row pull-right" style="margin-right: 20px; color: red">Delete Row</button> -->
                                    </div>
                                </div>
                            </div> 
                          </div> 
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="table-responsive p-2">
                    <table class="table table-bordered pt-2" id="dataTable">
                      <thead class="text-nowrap">
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center">Diagnosis Code</th>
                          <th class="text-center"><?php echo $th2; ?></th>
                          <th class="text-left w-50"><?php echo $th4; ?></th>
                          <th class="text-left w-50"><?php echo $th3; ?></th>
                          <th class="text-center"><?php echo $th5; ?></th>
                          <!-- <th class="text-center">< ?php echo $th6; ?></th> -->
                        </tr>
                      </thead>
                      <tbody class="text-nowrap"></tbody>
                    </table>
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
    <script src="../script/page_notification_diagnosis.js"></script>
    <script type="text/javascript">
      window.onload = function(){ 
        var activeMenu = document.getElementById("Patients");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("Patient List");
        activeSubMenu.classList.add("active");

        var activeTabMenu = document.getElementById("notification-prescription");
        activeTabMenu.classList.add("active");
      };
    </script>
  </foot>
</html> 
<script type="text/javascript"> 
    var i=6;
    var _id=0;
    var _form = 0;
    var _type_id = 0;
    $(document).ready(function(){ 
        $(".add-row").click(function(){
            var markup = "<tr id='" + i + "'>" +
                "   <td style='padding: 3px;text-align:center'>" + i + "</td>" +
                "   <td style='padding: 3px;'><input type='text' class='form-control pull-left' name='medicine[]' /></td>" +
                "   <td style='padding: 3px;'>" +
                "      <select class='form-select' name='instruction[]'>" + 
                "        <option value='1'>After Meal</option>" +
                "        <option value='2'>Before Meal</option>" +
                "      </select>" + 
                "   </td>" +
                "   <td style='padding: 3px;'><input type='number' name='morning[]' min='0' value='1' style='text-align: center' class='form-control'/></td>" +
                "   <td style='padding: 3px;'><input type='number' name='afternoon[]' min='0' value='1' style='text-align: center' class='form-control'/></td>" +
                "   <td style='padding: 3px;'><input type='number' name='evening[]' min='0' value='1' style='text-align: center' class='form-control'/></td>" +
                "   <td style='padding: 3px;'><input type='number' name='duration[]' min='0' value='3' style='text-align: center' class='form-control'/></td>" +
                "   <td style='padding: 3px;text-align:center'><button type='button' onClick='onDelete("+i+");' style='color: red'>Delete</button></td>" +
                "</tr>";
            $("table#order_items-tbl tbody").append(markup);
            i++;
        }); 
        //
        $("#type").on('change',function(e){
            onTypeItem($("#type").val());
        });

        // form submit
        $("#click_submit_sub1").on('click',function(evt){  
            //
            if($('#home').val()==''){
                $('#home').focus();
                return false;
            }
            if($('#street').val()==''){
                $('#street').focus();
                return false;
            } 
            if($('#phone_receive').val()==''){
                $('#phone_receive').focus();
                return false;
            }
            if($('#location').val()==''){
                $('#location').focus();
                return false;
            }
            if($('#receive_name').val()==''){
                $('#receive_name').focus();
                return false;
            }
            if($('#date').val()==''){
                $('#date').focus();
                return false;
            }
            if($('#due_date').val()==''){
                $('#due_date').focus();
                return false;
            }
            //
            _arr.push({name: 'home',    value: $('#home').val()});
            _arr.push({name: 'street',  value: $('#street').val()});
            _arr.push({name: 'phone_receive', value: $('#phone_receive').val()}); 

            _arr.push({name: 'location',    value: $('#location').val()});
            _arr.push({name: 'receive_name',value: $('#receive_name').val()});
            _arr.push({name: 'date', value: $('#date').val()});
            _arr.push({name: 'due_date',  value: $('#due_date').val()});
            //_arr.push({name: 'time', value: $('#time').val()});

            _arr.push({name: 'isPackage',   value: $('#isPackage').val()});
            _arr.push({name: 'color',       value: $('#color').val()});
            _arr.push({name: 'pmam',  value: $('#pmam').val()});
            _arr.push({name: 'service_charge',  value: $('#service_charge').val()});
            _arr.push({name: 'text_display',    value: $('#text_display').val()});
            _arr.push({name: 'desc_receive',     value: $('#desc_receive').val()}); 
            $('#frm_modal_sub1').modal('hide');
        }); 
    });
    function onDelete(id){
          $('table#order_items-tbl tbody tr#'+id+'').remove();
        }
    //
    function onTypeItem(type_id) { 
        _form = 2;
        _type_id = type_id;
        if(type_id==1){
            $('#divTempPickUp').css('display', 'block');
            $('#divTempDelivery').css('display', 'none');
        }
        else{
            $('#divTempPickUp').css('display', 'none');
            $('#divTempDelivery').css('display', 'block');
        }
        
        _arr.push({name: 'type_id',    value: type_id});
        //
        $.ajax({
            url: urlBase+"/services/public/order/v1/form_type_item?type_id="+type_id,
            type: 'POST',
            data    : _arr,
            dataType: 'html', 
            success: function(html) {
                $('#frm_modal_sub1').find('.modal-title').html('Instruction');
                $('#frm_modal_sub1').find('.modal-body').html(html);
                $('#frm_modal_sub1').find('.modal-dialog').css('width','50%');
                $('#frm_modal_sub1').find('.modal-dialog').css('max-width','100%'); 
                $('#frm_modal_sub1').find('#click_submit_sub1').css('display','block');
                $('#frm_modal_sub1').find('#click_submit_sub1').data("state", "create").html('<i class="fa fa-save"></i>&nbsp; Submit');
                $('#frm_modal_sub1').modal({backdrop: 'static',keyboard: false});
                // $('#frm_modal_sub1').find('.modal-footer').css('display','block');
                $('#frm_modal_sub1').modal('show');
            }
        });
    } 
 
 
</script>