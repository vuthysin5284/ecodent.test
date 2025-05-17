let _table = null;
let _status = "1";
$(document).ready(function () {
  $("#contact").on("input", function (e) {
    $(this).val(
      $(this)
        .val()
        .replace(/[^0-9]/g, "")
    );
  });
  getDataTable();
});

$(document).on("click", "#editPatientID", function () {
  if ($("#editPatientID").val() == 1) {
    $("#iconPatientID").attr("class", "bx bx-check");
    $("#id").attr("readonly", false);
    $("#id").focus();
    $("#editPatientID").val(0);
  } else {
    $("#iconPatientID").attr("class", "bx bx-pencil");
    $("#id").attr("readonly", true);
    $("#editPatientID").val(1);
  }
});

$(document).on("click", ".qrBtn", function () {
  var table = $("#dataTable").DataTable();
  var id = $(this).data("id");
  $.ajax({
    url: "../data/query_patient_list.php",
    data: { qid: 6, id: id },
    type: "POST",
  });
});

$(document).on("click", "#create", function () {
   // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('patientOffcanvas'));
    offcanvas.show();

  // $(".modal-dialog form").attr("id", "addForm");
  // clearForm();
  // $("#code").val(randQr(8));
  // $.ajax({
  //   url: "../data/query_patient_list.php",
  //   data: { qid: 5 },
  //   type: "POST",
  //   success: function (data) {
  //     var json = JSON.parse(data);
  //     var id = json.id;
  //     if (id == null) {
  //       id = 1;
  //     } else {
  //       id = parseInt(id) + 1;
  //     }
  //     var sid = "P-" + padZero(id, 5);
  //     $("#id").val(sid);
  //   },
  // });
});

$(document).on("submit", "#addForm", function (e) {
  e.preventDefault();
  var uid = $("#navLog").val();
  var clinic_id = $("#business_id").val();
  var formData = new FormData(this);
  formData.append("qid", 3);
  formData.append("uid", uid);
  formData.append("clinic_id", clinic_id);
  $.ajax({
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener(
        "progress",
        function (evt) {
          if (evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            percentComplete = parseInt(percentComplete * 100);
            $("#progressbar").html(percentComplete + "%");
            $("#progressbar").width(percentComplete + "%");
          }
        },
        false
      );
      return xhr;
    },
    type: "POST",
    enctype: "multipart/form-data",
    url: "../data/query_patient_list.php",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: function (data) {
      var json = JSON.parse(data);
      var status = json.status;
      if (status == "true") {
        clearForm();
        $("#progressbar").html(0 + "%");
        $("#progressbar").width(0 + "%");
        $("#Modal").modal("hide");
        $("#dataTable").DataTable().draw();
        alertText("Patient has been saved to database!", "primary");
      } else {
        alert("Failed");
      }
    },
  });
});

$("#dataTable").on("click", ".editBtn", function () { 
   // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('patientOffcanvas'));
    offcanvas.show();
  // var table = $("#dataTable").DataTable();
  // var trid = $(this).closest("tr").attr("id");
  // var id = $(this).data("id");
  // clearForm();
  // $("#Modal").modal("show");
  // $(".modal-dialog form").attr("id", "editForm");
  // $.ajax({
  //   url: "../data/query_patient_list.php",
  //   data: { qid: 2, id: id },
  //   type: "post",
  //   success: function (data) {
  //     var json = JSON.parse(data);
  //     var cid = "P-" + padZero(json.id, 5);
  //     var gender = json.cust_gender;
  //     var img = json.cust_image;
  //     var now = new Date();
  //     var dob = new Date(json.cust_dob);
  //     var age = now.getFullYear() - dob.getFullYear();
  //     var folder = "";
  //     if (img != "0") {
  //       folder = cid + "/";
  //     }
  //     if (gender == 1) {
  //       $("#gender").val("1").change();
  //     } else {
  //       $("#gender").val("0").change();
  //     }
  //     $("#id").val(cid);
  //     $("#code").val(json.cust_code);
  //     $("#name").val(json.cust_fname);
  //     $("#dob").val(json.cust_dob);
  //     $("#age").val(age);
  //     $("#contact").val(json.cust_contact);
  //     $("#email").val(json.cust_email);
  //     $("#membership").val(json.memb_id);
  //     $("#address").val(json.cust_address);
  //     $("#dentist").val(json.dentist_id);
  //     $("#datetime").val(json.timestamp);
  //     $("#uploadedAvatar").attr(
  //       "src",
  //       "../images/profiles/" + folder + img + ".jpg"
  //     );
  //   },
  // });
});

$(document).on("submit", "#editForm", function (e) {
  e.preventDefault();
  var formData = new FormData(this);
  formData.append("qid", 4);
  $.ajax({
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener(
        "progress",
        function (evt) {
          if (evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            percentComplete = parseInt(percentComplete * 100);
            $("#progressbar").html(percentComplete + "%");
            $("#progressbar").width(percentComplete + "%");
          }
        },
        false
      );
      return xhr;
    },
    type: "POST",
    enctype: "multipart/form-data",
    url: "../data/query_patient_list.php",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: function (data) {
      var json = JSON.parse(data);
      var status = json.status;
      if (status == "true") {
        clearForm();
        $("#progressbar").html(0 + "%");
        $("#progressbar").width(0 + "%");
        $("#Modal").modal("hide");
        $("#dataTable").DataTable().draw();
        alertText("Patient has been updated to database!", "primary");
      } else {
        alert("Failed");
      }
    },
  });
});

$(document).on("click", ".deleteBtn", function () {
  var table = $("#dataTable").DataTable();
  var id = $(this).data("id");
  if (confirm("Are you sure want to delete this menu? ")) {
    $.ajax({
      url: "../data/query_patient_list.php",
      data: { qid: 0, id: id },
      type: "POST",
      success: function (data) {
        var json = JSON.parse(data);
        var status = json.status;
        if (status == "success") {
          $("#dataTable").DataTable().ajax.reload();
          alertText("Patient has been deleted from database!", "primary");
        } else {
          alert(data);
          return;
        }
      },
    });
  } else {
    return null;
  }
});

function getDataTable() {
  var clinic_id = $("#business_id").val();
  $("#dataTable").DataTable({
    fnCreatedRow: function (nRow, aData, iDataIndex) {
      $(nRow).attr("id", aData[0]);
    },
    drawCallback: function () {
      $('[data-bs-toggle="tooltip"]').tooltip();
    },
    dom: '<"d-flex justify-content-between px-6"<"d-flex justify-content-start justify-content-md-end align-items-baseline mb-0"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-0"lB>><""f>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    language: {
      sLengthMenu: "_MENU_",
      search: "",
      searchPlaceholder: "Search", 
      "loadingRecords": "&nbsp;",
      "processing": "Loading..." 
    },
    buttons: [
      {
        extend: "collection",
        className: "dropdown-toggle btn btn-primary shadow-none ms-2",
        text: "Status",
        buttons: [
          {
            text: "All",
            class: "dropdown-item",
            action: function (e, dt, node, config) {
              _status = "ALL";
              _table.ajax.reload();
            },
          },
          {
            text: "Active",
            class: "dropdown-item",
            action: function (e, dt, node, config) {
              _status = "1";
              _table.ajax.reload();
            },
          },
          {
            text: "Delete",
            class: "dropdown-item",
            action: function (e, dt, node, config) {
              _status = "0";
              _table.ajax.reload();
            },
          },
        ],
      },
      {
        extend: "collection",
        className: "dropdown-toggle btn btn-primary shadow-none",
        text: "Member",
        buttons: [
          {
            text: "ALL",
            action: function (e, dt, node, config) {
              _num_code = "ALL";
              _table.ajax.reload();
            },
          },
          {
            text: "Normal",
            action: function (e, dt, node, config) {
              _num_code = "Normal";
              _table.ajax.reload();
            },
          },
          {
            text: "VIP",
            action: function (e, dt, node, config) {
              _num_code = "VIP";
              _table.ajax.reload();
            },
          },
          {
            text: "Farmily",
            action: function (e, dt, node, config) {
              _num_code = "Farmily";
              _table.ajax.reload();
            },
          },
        ],
      },
      {
        extend: "collection",
        className: "dropdown-toggle btn btn-primary shadow-none",
        text: '<i class="bx bx-export me-2"></i>Export',
        buttons: [
          {
            extend: "excelHtml5",
            text: '<i class="bx bx-file me-2"></i>Excel',
            className: "dropdown-item",
            exportOptions: { columns: [0, 1, 2, 3, 4] },
          },
          {
            extend: "colvis",
            text: '<i class="bx bx-grid me-2"></i>Column',
            className: "dropdown-item",
          },
        ],
      },{
          className: 'btn btn-primary ms-2',
          text: '<i class="bx bx-user-plus me-2"></i> Patient',
          action: function (e, dt, node, config) { $('#create').trigger('click'); }
        }
    ],
    pageLength: 25,
    serverSide: true,
    processing: true,
    searching: true,
    paging: true,
    order: [],
    ajax: {
      url: "../data/query_patient_list.php",
      type: "POST",
      data: { qid: 1, clinic_id: clinic_id },
    },
    aoColumnDefs: [
      { bSortable: false, aTargets: [] },
      { bVisible: false, aTargets: [] },
    ],
  });
}

$("#dob").change(function () {
  var now = new Date();
  var dob = new Date($(this).val());
  var age = now.getFullYear() - dob.getFullYear();
  $("#age").val(age);
});

$("#age").change(function () {
  var now = new Date();
  var age = $(this).val();
  var year = now.getFullYear() - age;
  var text = year + "-01-01";
  var dob = new Date(text);
  $("#dob").val(text);
});

function clearForm() {
  var now = new Date();
  var timezoneOffset = 7 * 60;
  var offsetDateTime = new Date(now.getTime() + timezoneOffset * 60000);
  var formattedDateTime = offsetDateTime
    .toISOString()
    .slice(0, 16)
    .replace("T", " ");
  var defaultdob = new Date("1998-01-01");
  var age = now.getFullYear() - defaultdob.getFullYear();
  $("#age").val(age);
  $("#upload").val("");
  $("#name").val("");
  $("#contact").val("");
  $("#address").val("");
  $("#gender").val("1");
  $("#dentist").val(2);
  $("#membership").val(3);
  $("#dob").val("1998-01-01");
  $("#id").attr("readonly", true);
  $("#datetime").val(formattedDateTime);
  $("#iconPatientID").attr("class", "bx bx-pencil");
  $("#uploadedAvatar").attr("src", "../images/profiles/0.jpg");
}
