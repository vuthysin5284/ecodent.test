$(document).ready(function () {
  getBusinessRegistrationData();
  $("#phone").on("input", function (e) {
    $(this).val(
      $(this)
        .val()
        .replace(/[^0-9]/g, "")
    );
  });
});

$("#confirm_password").on("input", function (e) {
  var password = $("#password").val();
  var confirmPassword = $(this).val();
  if (confirmPassword.length > 0) {
    if (password !== confirmPassword) {
      $("#password_status").html("Password and confirm password do not match.");
    } else {
      $("#password_status").html("");
    }
  } else {
    $("#password_status").html("");
  }
});

$("#formUserRegistration").on("submit", function (e) {
  e.preventDefault();
  var password = $("#password").val();
  var confirmPassword = $("#confirm_password").val();
  if (password !== confirmPassword) {
    $("#password_status").html("Password and confirm password do not match.");
    $("#confirm_password").focus();
  } else {
    var formData = new FormData(this);
    formData.append("qid", 1);
    // Send the data to the server using AJAX
    $.ajax({
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      enctype: "multipart/form-data",
      url: "../api/v1/clinic/user-register.php",
      success: function (data) {
        var json = JSON.parse(data);
        var status = json.status;
        if (status == "success") {
          window.location.href = "login.php";
        }
      },
      error: function (error) {
        alert("Registration failed. Please try again.");
        console.error(error);
      },
    });
  }
});

function getBusinessRegistrationData() {
  var token = $("#token").val();
  $.ajax({
    type: "POST",
    url: "../api/v1/clinic/clinic-manage.php",
    data: { token: token, qid: 5 },
    success: function (data) {
      var json = JSON.parse(data);
      $("#phone").val(json.phone);
      $("#email").val(json.email);
    },
    error: function (error) {
      console.error("Error fetching business registration data:", error);
    },
  });
}
