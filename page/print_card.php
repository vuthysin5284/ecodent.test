<?php
  session_start();
  $log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
  if (!isset($log)) { header('Location: login.php'); }
  error_reporting(0);
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
      $page_title = 'Invoices';
      include_once('../inc/config.php');
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
    <style type="text/css">
      .invoice-wrapper { 
        width : 1098px;
        height : 659px;
        margin : 0px auto;
        background-color : #fff;
        background-image : url('../assets/img/backgrounds/card_bg.png');
      }
      .invoice-container {
        padding : 30px;
      }
    </style>
  </head>
  <body>
    <div class="invoice-wrapper">
      
    </div>
    <?php include_once('../inc/footer.php'); ?> 
  </body>
</html>