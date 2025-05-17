<?php
  $page = 'Menu Category';
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
      $lang = 1;
      if ($lang == 1) {
        $page_category = 'Setting';
        $page_title = 'Menu';
        $text_btn_create = 'New';
        $text_form = 'Menu';
        $m1 = 'Menu ID';
        $m2 = 'Menu Category';
        $m3 = 'Menu Title (En)';
        $m4 = 'Menu Title (Kh)';
        $m5 = 'Menu URL';
        $m6 = 'Menu Order';
        $th2 = 'Menu Icon';
        $th3 = 'Menu Category';
        $th4 = 'Menu Title (En)';
        $th5 = 'Menu Title (Kh)';
        $th6 = 'Menu URL';
        $th7 = 'Menu Order';
        $th8 = 'Action';
      } else {
        $page_category = 'ការកំណត់';
        $page_title = 'មាតិការ';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'មាតិការ';
        $m1 = 'លេខកូដ';
        $m2 = 'មាតិការ';
        $m3 = 'ចំណងជើង (អង់គ្លេស)';
        $m4 = 'ចំណងជើង (ខ្មែរ)';
        $m5 = 'រូបភាព';
        $m6 = 'លេខលំដាប់';
        $th2 = 'រូបភាព';
        $th3 = 'មាតិការ';
        $th4 = 'ចំណងជើង (អង់គ្លេស)';
        $th5 = 'ចំណងជើង (ខ្មែរ)';
        $th6 = 'តំណភ្ជាប់';
        $th7 = 'លេខលំដាប់';
        $th8 = 'ដំណើរការ';
      }
      include_once('../inc/config.php');
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
              <div class="d-flex justify-content-between">
                <div class="me-auto">
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light">Business List</span>
                  </h4>
                </div>
                <div class="p-2">
                  <button type="button" id="create" class="btn btn-dark" data-bs-toggle="offcanvas"  data-bs-target="#offcanvasEnd" aria-controls="offcanvasEnd">
                    <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; Create</span>
                      <i class="bx bx bx-plus d-block d-sm-none"></i>
                  </button>
                </div>
              </div>
            
              <div class="card">
                <div class="card-body py-3 px-0">
                  <div class="table-responsive-md py-2 px-0">
                    <table class="table pt-2 mx-auto" id="dataTable">
                      <thead class="text-nowrap table-border-bottom-0">
                        <tr>
                          <th>ID</th>
                          <th>Title</th>
                          <th>Parent</th>
                          <th>Icon</th>
                          <th>URL</th>
                          <th>Group</th>
                          <th>Permission</th>
                          <th>Active</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody class="text-nowrap"></tbody>
                    </table>
                  </div>  
                </div>
              </div>
            </div>
            
            <!-- Sidebar Preview -->
            <div class="card mt-3">
              <div class="card-header">
                <h5 class="mb-0">Sidebar Menu Preview</h5>
              </div>
              <div class="card-body">
                <div class="menu menu-vertical demo-sidebar">
                  <ul class="menu-inner py-1" id="menuPreview"></ul>
                </div>
              </div>
            </div>

            <!-- Offcanvas Form -->
            <div class="canvas_form">
              <form class="" id="addForm" method="POST" enctype="multipart/form-data">
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel" data-bs-backdrop="static">
                  <div class="offcanvas-header">
                    <h5 id="offcanvasEndLabel" class="offcanvas-title">Create New Clinics</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <hr class="my-0">
                  <div class="offcanvas-body">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                      <label class="form-label">Title</label>
                      <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Parent Menu</label>
                      <select class="form-select" name="parent_id" id="parent_id"></select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Label Group</label>
                      <input type="text" class="form-control" name="label_group" id="label_group">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Icon</label>
                      <input type="text" class="form-control" name="icon" id="icon">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">URL</label>
                      <input type="text" class="form-control" name="url" id="url">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Permission Key</label>
                      <input type="text" class="form-control" name="permission_key" id="permission_key">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Order Index</label>
                      <input type="number" class="form-control" name="sort_order" id="sort_order" min="0">
                    </div>
                    <div class="row mb-3">
                      <div class="col">
                        <label class="form-label">Dropdown</label>
                        <select class="form-select" name="is_dropdown" id="is_dropdown">
                          <option value="0">No</option>
                          <option value="1">Yes</option>
                        </select>
                      </div>
                      <div class="col">
                        <label class="form-label">Active</label>
                        <select class="form-select" name="is_active" id="is_active">
                          <option value="1">Yes</option>
                          <option value="0">No</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <hr class="my-0">
                  <div class="offcanvas-footer p-5 d-flex align-items-center justify-content-between gap-2">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Close</button>
                    <button type="submit" class="btn btn-dark">Save</button>
                  </div>
                </div>
              </form>
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
    <script src="../script/setting-menu.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Setting");
        activeMenu.classList.add("active");
      };
    </script>
  </foot>
</html>
