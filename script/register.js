$(document).ready(function () {
  $("#phone").on("input", function (e) {
    $(this).val(
      $(this)
        .val()
        .replace(/[^0-9]/g, "")
    );
  });

  $("#name").on("input", function (e) {
    // Generate slug from name input
    var name = $(this).val();
    var slug = generateSlug(name);
    $("#slug").val(slug);
    checkSlug(slug);
  });

  function checkSlug(slug) {
    $.ajax({
      type: "POST",
      url: "../api/v1/clinic/clinic-manage.php",
      data: { slug: slug, qid: 6 },
      success: function (data) {
        var json = JSON.parse(data);
        if (json.status == "available") {
          $("#slug-status").html("");
        } else {
          $("#slug-status").html(
            "This domain `" + slug + "` is already taken."
          );
          $("#slug").val("");
        }
      },
      error: function (error) {
        console.error("Error checking slug:", error);
      },
    });
  }

  $("#slug").on("input", function (e) {
    var slug = $(this).val();
    if (slug.length > 0) {
      checkSlug(slug);
    } else {
      $("#slug-status").html("");
    }
  });

  $("#formBusinessRegistration").on("submit", function (e) {
    e.preventDefault();
    var tokens = generateToken(32);
    var formData = new FormData(this);
    formData.append("token", tokens);
    formData.append("qid", 1);
    // Send the data to the server using AJAX
    $.ajax({
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      enctype: "multipart/form-data",
      url: "../api/v1/clinic/clinic-manage.php",
      success: function (data) {
        var json = JSON.parse(data);
        var token = json.token;
        var status = json.status;
        if (status == "success") {
          window.location.href = "user-register.php?token=" + token;
        }
      },
      error: function (error) {
        alert("Registration failed. Please try again.");
        console.error(error);
      },
    });
  });
});

// function to generate unique token
function generateToken(length) {
  var characters =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  var token = "";
  for (var i = 0; i < length; i++) {
    token += characters.charAt(Math.floor(Math.random() * characters.length));
  }
  return token;
}

// function to generate unique slug
function generateSlug(name) {
  return name
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-|-$/g, "");
}
