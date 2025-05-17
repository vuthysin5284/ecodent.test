<?php
  session_start();
  include_once ("../inc/config.php");
	include_once ("../securimage/securimage.php");
  $page = 'Login';
?>
<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
  	<?php
  		$header_title = 'Login';
      include ("../inc/header.php");
      $bg = array( '1.jpg',  '2.jpg');
      $i = rand(0, count($bg)-1);
      $selectedBg = "$bg[$i]";
  	?>
  </head>
  <body style="background:#EFEAE3 url(../assets/img/backgrounds/<?php echo $selectedBg?>);">
    <div class="position-fixed progress top-0 left-0 w-100">
      <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
    </div>
    <div class="container col-xl-3 col-md-8 col-xs-12" style="padding-top : 5%; ">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <div class="card">
            <div class="card-body">
              <div class="app-brand justify-content-center">
                <a href="index.php" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo"><img src="../assets/img/icons/brands/logo_alt.png" alt="logo" height="80"></span>
                  <span class="app-brand-text demo text-body fw-bolder"></span>
                </a>
              </div>
              <h4 class="mb-2"></h4>
              <p class="mb-4"></p>
              <form role="form" id="loginForm" class="mb-3" method="POST" enctype="multipart/form-data">
                <fieldset>
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" autofocus required />
                  </div>
                  <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                      <label class="form-label" for="password">Password</label>   
                    </div>
                    <div class="input-group input-group-merge">
                      <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                      <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                  </div>
                  <!-- <div class="mb-3">
                    <div>
                      <img id="siimage" src="../securimage/securimage_show.php?sid='<?=md5(uniqid())?>'" alt="CAPTCHA Image" align="middle">
                        <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image">
                          <span class="icon-cycle" aria-hidden="true" onClick="document.getElementById('siimage').src = 'securimage/securimage_show.php?sid=' + Math.random();"></span>
                        </a>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="ccode" class="form-label">Security</label>
                    <input type="text" class="form-control" id="ccode" name="ccode" placeholder="Verifying text from the captcha box" required />
                  </div> -->
                  <div class="mb-3">
                    <button class="btn btn-primary d-grid w-100" type="submit" name="Submit" value="Login">Sign in</button>
                  </div>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <foot>
    <?php include_once ("../inc/footer.php"); ?>
    <script src="../script/page_login.js"></script>
  </foot>
</html>
