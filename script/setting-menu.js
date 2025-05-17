$(document).ready(function () {
  getDataTable();
  loadParentMenuOptions();
  loadMenuPreview();
});

function getDataTable() {
  $("#dataTable").DataTable({
    fnCreatedRow: function (nRow, aData, iDataIndex) {
      $(nRow).attr("id", aData[0]);
    },

    dom: '<"d-flex justify-content-between px-6"<"d-flex justify-content-start justify-content-md-end align-items-baseline mb-0"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-0"lB>><""f>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    language: {
      sLengthMenu: "_MENU_",
      search: "",
      searchPlaceholder: "Search",
    },
    buttons: [
      {
        extend: "collection",
        className: "dropdown-toggle btn btn-dark shadow-none ms-2",
        text: '<i class="bx bx-export me-2"></i>Export',
        buttons: [
          {
            extend: "excelHtml5",
            text: '<i class="bx bx-file me-2"></i>Excel',
            className: "dropdown-item",
            exportOptions: { columns: [] },
          },
          {
            extend: "colvis",
            text: '<i class="bx bx-grid me-2"></i>Column',
            className: "dropdown-item",
          },
        ],
      },
    ],
    pageLength: 25,
    serverSide: true,
    processing: true,
    searching: true,
    paging: true,
    order: [],
    ajax: {
      url: "../api/v1/clinic/setting-menu.php",
      type: "POST",
      data: { qid: 1 },
    },
    aoColumnDefs: [
      { bSortable: false, aTargets: [] },
      { bVisible: false, aTargets: [] },
    ],
  });
}

function loadParentMenuOptions() {
  $.post("../api/v1/clinic/setting-menu.php", { qid: 4 }, function (data) {
    let options = `<option value="">None (Top Level)</option>`;
    JSON.parse(data).forEach((item) => {
      options += `<option value="${item.id}">${item.title}</option>`;
    });
    $("#parent_id").html(options);
  });
}

function loadMenuPreview() {
  $.post("../api/v1/clinic/setting-menu.php", { qid: 5 }, function (data) {
    let html = "";
    var res = JSON.parse(data);
    Object.keys(res).forEach((group) => {
      html += `
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text" data-i18n="">${group}</span>
        </li>`;
      res[group].forEach((item) => {
        if (item.is_dropdown == "1") {
          html += `
            <li class="menu-item open">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ${item.icon}"></i> 
                <div class="text-truncate" data-i18n="">${item.title}</div>
              </a>
              <ul class="menu-sub">`;
          if (item.children.length > 0) {
            item.children.forEach((child) => {
              html += `
                <li class="menu-item">
                  <a href="javascript:void(0);" class="menu-link">
                    <i class="menu-icon tf-icons ${child.icon}"></i> 
                    <div class="text-truncate" data-i18n="">${child.title}</div>
                    </a>
                </li>`;
            });
          }
          html += `</ul></li>`;
        } else {
          html += `
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link">
                <i class="menu-icon tf-icons ${item.icon}"></i>
                <div class="text-truncate" data-i18n="">${item.title}</div>
              </a>
            </li>`;
        }
      });
    });

    $("#menuPreview").html(html);
  });
}

$(document).on("click", "#create", function () {
  clearForm();
  $("#offcanvasEndLabel").text("Create Sidebar Item");
  $("#offcanvasEnd").offcanvas("show");
});

$(document).on("click", ".editBtn", function () {
  const id = $(this).data("id");
  $("#offcanvasEnd").offcanvas("show");
  $.post(
    "../api/v1/clinic/setting-menu.php",
    { qid: 2, id: id },
    function (data) {
      const d = JSON.parse(data);
      $("#id").val(d.id);
      $("#title").val(d.title);
      $("#icon").val(d.icon);
      $("#url").val(d.url);
      $("#label_group").val(d.label_group);
      $("#parent_id").val(d.parent_id);
      $("#permission_key").val(d.permission_key);
      $("#sort_order").val(d.sort_order);
      $("#is_dropdown").val(d.is_dropdown);
      $("#is_active").val(d.is_active);
      $("#offcanvasEndLabel").text("Edit Sidebar Item");
      $("#offcanvasEnd").offcanvas("show");
    }
  );
});

$(document).on("submit", "#addForm", function (e) {
  e.preventDefault();
  const formData = new FormData(this);
  formData.append("qid", 3);
  $.ajax({
    type: "POST",
    url: "../api/v1/clinic/setting-menu.php",
    data: formData,
    contentType: false,
    processData: false,
    success: function (data) {
      const res = JSON.parse(data);
      if (res.status == "success") {
        $("#offcanvasEnd").offcanvas("hide");
        $("#dataTable").DataTable().ajax.reload();
      } else {
        alert("Failed to save.");
      }
    },
  });
});

$(document).on("click", ".deleteBtn", function () {
  const id = $(this).data("id");
  if (confirm("Are you sure you want to delete this item?")) {
    $.post(
      "../api/v1/clinic/setting-menu.php",
      { qid: 0, id: id },
      function (data) {
        const res = JSON.parse(data);
        if (res.status == "success") {
          $("#dataTable").DataTable().ajax.reload();
        } else {
          alert("Delete failed.");
        }
      }
    );
  }
});

function clearForm() {
  $("#addForm")[0].reset();
  $("#id").val("");
}
