<?php

  if (!empty($_POST["btnsubmit"])) {
  	// if the form has been submitted
    $captcha = @$_POST['ct_captcha']; // the user's entry for the captcha code
	
      require_once dirname(__FILE__) . '/securimage.php';
      $securimage = new Securimage();
      
      if ($securimage->check($captcha) == false) {
		 //============error=1=========================
        $kk='<strong style="color:#F00">Errorrrrrrr</strong>';
      }else{
		  //============ok=2=========================
		$kk='<strong style="color:#090">OKkkkk</strong>';
	  }
  }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Securimage Example Form</title>
</head>
<body>
<form method="post" action="" id="contact_form" name="contact_form">

    <img id="siimage" style="border: 1px solid #000; margin-right: 15px" src="./securimage_show.php?sid=<? echo md5(uniqid()); ?>" alt="CAPTCHA Image" align="left">
    <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = './securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="./images/refresh.png" alt="Reload Image" onclick="this.blur()" align="bottom" border="0"></a><br />
    <br>    
    <br>
    <br>
    <? echo $kk; ?>
<input type="text" name="ct_captcha" size="12" maxlength="4" />
    <br />
<input type="submit" name="btnsubmit" value="Submit">
</form>
</body>
</html>