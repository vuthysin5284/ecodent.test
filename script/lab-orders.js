$(document).ready(function () {
  // Initialize date range picker
  if ($("#filterDateRange").length) {
    $("#filterDateRange").daterangepicker({
      opens: "left",
      autoUpdateInput: false,
      locale: { cancelLabel: "Clear", format: "MM/DD/YYYY" },
    });
    $("#filterDateRange").on("apply.daterangepicker", function (ev, picker) {
      $(this).val(
        picker.startDate.format("MM/DD/YYYY") +
          " - " +
          picker.endDate.format("MM/DD/YYYY")
      );
      loadLabOrders();
    });
    $("#filterDateRange").on("cancel.daterangepicker", function () {
      $(this).val("");
      loadLabOrders();
    });
  }

  // Event listeners
  $("#filterForm").submit(function (e) {
    e.preventDefault();
    loadLabOrders();
  });

  $("#labOrderForm").submit(function (e) {
    e.preventDefault();
    submitLabOrder();
  });

  $("#updateStatusForm").submit(function (e) {
    e.preventDefault();
    updateOrderStatus();
  });

  $("#confirmDelete").click(function () {
    var orderId = $("#deleteOrderId").val();
    deleteLabOrder(orderId);
  });

  $("#btnEditOrder").click(function () {
    $("#viewLabOrderModal").modal("hide");
    var orderNumber = $("#viewOrderNumber").text();
    var orderId = orderNumber.replace("LAB-", "");
    editLabOrder(orderId);
  });
$("#createLabOrderModal").click(function () {
    // $("#labOrderForm")[0].reset();
    // $("#orderId").val("");
    // $("#modalTitle").text("Create Lab Order");
   // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('laborderOffcanvas'));
    offcanvas.show();

    console.log('test');
  });
  
  $("#createLabOrderModal").on("hidden.bs.modal", function () {
    // $("#labOrderForm")[0].reset();
    // $("#orderId").val("");
    // $("#modalTitle").text("Create Lab Order");
   // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('laborderOffcanvas'));
    offcanvas.show();

    console.log('test');
  });

  // Initial load
  loadLabOrders();

  // CRUD Functions

  function loadLabOrders() {
    var filters = {
      dateRange: $("#filterDateRange").val(),
      status: $("#filterStatus").val(),
      priority: $("#filterPriority").val(),
      search: $("#filterSearch").val(),
    };
    var $tbody = $("#labOrdersTable tbody");
    $tbody.html(
      '<tr><td colspan="8" class="text-center py-3"><div class="spinner-border text-primary"></div></td></tr>'
    );
    $.get(
      "../api/v1/clinic/lab-orders.php",
      $.extend({ action: "list" }, filters),
      function (data) {
        // if (data.success) {
        //   renderLabOrdersTable(data.orders);
        // } else {
        //   showToast(
        //     "error",
        //     "Error",
        //     data.message || "Failed to load lab orders"
        //   );
        //   $tbody.html(
        //     '<tr><td colspan="8" class="text-center py-3">Failed to load lab orders</td></tr>'
        //   );
        // }
        alert(data);
      },
      "json"
    ).fail(function (data) {
      $tbody.html(
        '<tr><td colspan="8" class="text-center py-3">An error occurred while loading data</td></tr>'
      );
      console.error(data);
    });
  }

  function renderLabOrdersTable(orders) {
    var $tbody = $("#labOrdersTable tbody");
    $tbody.empty();
    if (!orders.length) {
      $tbody.html(
        '<tr><td colspan="8" class="text-center py-3">No lab orders found</td></tr>'
      );
      return;
    }
    $.each(orders, function (i, order) {
      var statusBadge = getStatusBadge(order.status);
      var priorityBadge = getPriorityBadge(order.priority);
      var requestDate = new Date(order.request_date).toLocaleDateString();
      var dueDate = new Date(order.due_date).toLocaleDateString();
      var row = `<tr>
        <td><a href="javascript:void(0)" onclick="viewLabOrder(${
          order.id
        })" class="fw-semibold">LAB-${String(order.id).padStart(
        4,
        "0"
      )}</a></td>
        <td>${order.patient_name}</td>
        <td>${requestDate}</td>
        <td>${dueDate}</td>
        <td>${order.work_type}</td>
        <td>${priorityBadge}</td>
        <td>${statusBadge}</td>
        <td>
          <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="javascript:void(0);" onclick="viewLabOrder(${
                order.id
              })"><i class="bx bx-show-alt me-1"></i> View</a>
              <a class="dropdown-item" href="javascript:void(0);" onclick="editLabOrder(${
                order.id
              })"><i class="bx bx-edit-alt me-1"></i> Edit</a>
              <a class="dropdown-item" href="javascript:void(0);" onclick="openStatusModal(${
                order.id
              }, '${
        order.status
      }')"><i class="bx bx-transfer-alt me-1"></i> Update Status</a>
              <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="confirmDeleteOrder(${
                order.id
              })"><i class="bx bx-trash me-1"></i> Delete</a>
            </div>
          </div>
        </td>
      </tr>`;
      $tbody.append(row);
    });
  }

  function submitLabOrder() {
    var form = $("#labOrderForm")[0];
    var formData = new FormData(form);
    var orderId = $("#orderId").val();
    formData.append("action", orderId ? "update" : "create");
    var $btn = $(form).find('[type="submit"]');
    var originalText = $btn.text();
    $btn
      .prop("disabled", true)
      .html(
        '<span class="spinner-border spinner-border-sm"></span> Processing...'
      );
    $.ajax({
      url: "../api/v1/clinic/lab-orders.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (data) {
        if (data.success) {
          $("#createLabOrderModal").modal("hide");
          showToast(
            "success",
            "Success",
            data.message || "Lab order saved successfully"
          );
          loadLabOrders();
        } else {
          showToast(
            "error",
            "Error",
            data.message || "Failed to save lab order"
          );
        }
      },
      error: function () {
        showToast("error", "Error", "An unexpected error occurred");
      },
      complete: function () {
        $btn.prop("disabled", false).text(originalText);
      },
    });
  }

  window.editLabOrder = function (orderId) {
    $("#modalTitle").text("Edit Lab Order");
    $("#orderId").val(orderId);
    $("#createLabOrderModal").modal("show");
    $.get(
      "../api/v1/clinic/lab-orders.php",
      { action: "get", id: orderId },
      function (data) {
        if (data.success) {
          var order = data.order;
          $("#requestDate").val(order.request_date);
          $("#dueDate").val(order.due_date);
          $("#priority").val(order.priority);
          $("#status").val(order.status);
          $("#patientId").val(order.patient_id);
          $("#doctorId").val(order.doctor_id);
          $("#workType").val(order.work_type);
          $("#material").val(order.material);
          $("#shade").val(order.shade);
          $("#instructions").val(order.instructions);
          $('input[name="teeth[]"]').prop("checked", false);
          if (order.teeth) {
            $.each(order.teeth.split(","), function (_, tooth) {
              $("#tooth" + tooth).prop("checked", true);
            });
          }
        } else {
          showToast(
            "error",
            "Error",
            data.message || "Failed to load lab order data"
          );
        }
      },
      "json"
    );
  };

  window.viewLabOrder = function (orderId) {
    $("#viewLabOrderModal").modal("show");
    $.get(
      "../api/v1/clinic/lab-orders.php",
      { action: "get", id: orderId },
      function (data) {
        if (data.success) {
          var order = data.order;
          $("#viewOrderNumber").text(
            "LAB-" + String(order.id).padStart(4, "0")
          );
          $("#viewRequestDate").text(
            new Date(order.request_date).toLocaleDateString()
          );
          $("#viewDueDate").text(new Date(order.due_date).toLocaleDateString());
          $("#viewPriority").html(getPriorityBadge(order.priority));
          $("#viewStatus").html(getStatusBadge(order.status));
          $("#viewPatient").text(order.patient_name);
          $("#viewDoctor").text(order.doctor_name);
          $("#viewWorkType").text(capitalize(order.work_type));
          $("#viewMaterial").text(capitalize(order.material));
          $("#viewShade").text(order.shade || "-");
          $("#viewTeeth").text(
            order.teeth && order.teeth.length
              ? order.teeth
              : "No specific teeth selected"
          );
          $("#viewInstructions").text(
            order.instructions && order.instructions.trim().length
              ? order.instructions
              : "No additional instructions provided"
          );
          renderTimeline(order.timeline || []);
          renderAttachments(order.attachments || []);
        } else {
          showToast(
            "error",
            "Error",
            data.message || "Failed to load lab order data"
          );
        }
      },
      "json"
    );
  };

  function renderTimeline(timeline) {
    var $timeline = $("#viewTimeline");
    $timeline.empty();
    if (!timeline.length) {
      $timeline.html(
        '<li class="timeline-item pb-0"><div class="timeline-event">No status updates yet</div></li>'
      );
      return;
    }
    $.each(timeline, function (i, item) {
      var isLast = i === timeline.length - 1;
      var statusIcon = getStatusIcon(item.status);
      var statusText = capitalize(item.status.replace(/_/g, " "));
      var li = `<li class="timeline-item ${isLast ? "pb-0" : ""}">
        <span class="timeline-point timeline-point-primary"><i class="bx ${statusIcon}"></i></span>
        <div class="timeline-event">
          <div class="timeline-header">
            <h6 class="mb-0">${statusText}</h6>
            <small class="text-muted">${new Date(
              item.date
            ).toLocaleString()}</small>
          </div>
          ${item.note ? `<p class="mb-0">${item.note}</p>` : ""}
        </div>
      </li>`;
      $timeline.append(li);
    });
  }

  function renderAttachments(attachments) {
    var $container = $("#viewAttachments");
    $container.empty();
    if (!attachments.length) {
      $container.html('<p class="text-muted mb-0">No attachments</p>');
      return;
    }
    var list = $('<ul class="list-unstyled mb-0"></ul>');
    $.each(attachments, function (_, att) {
      var fileIcon = getFileIcon(att.type);
      var li = `<li class="d-flex align-items-center mb-2">
        <i class="bx ${fileIcon} me-2"></i>
        <a href="${att.url}" target="_blank">${att.name}</a>
      </li>`;
      list.append(li);
    });
    $container.append(list);
  }

  window.openStatusModal = function (orderId, currentStatus) {
    $("#statusOrderId").val(orderId);
    $("#newStatus").val(currentStatus);
    $("#updateStatusModal").modal("show");
  };

  function updateOrderStatus() {
    var form = $("#updateStatusForm")[0];
    var formData = new FormData(form);
    formData.append("action", "updateStatus");
    var $btn = $(form).find('[type="submit"]');
    var originalText = $btn.text();
    $btn
      .prop("disabled", true)
      .html(
        '<span class="spinner-border spinner-border-sm"></span> Updating...'
      );
    $.ajax({
      url: "../api/v1/clinic/lab-orders.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (data) {
        if (data.success) {
          $("#updateStatusModal").modal("hide");
          showToast(
            "success",
            "Success",
            data.message || "Status updated successfully"
          );
          loadLabOrders();
        } else {
          showToast(
            "error",
            "Error",
            data.message || "Failed to update status"
          );
        }
      },
      error: function () {
        showToast("error", "Error", "An unexpected error occurred");
      },
      complete: function () {
        $btn.prop("disabled", false).text(originalText);
      },
    });
  }

  window.confirmDeleteOrder = function (orderId) {
    $("#deleteOrderId").val(orderId);
    $("#deleteLabOrderModal").modal("show");
  };

  function deleteLabOrder(orderId) {
    $.ajax({
      url: "../api/v1/clinic/lab-orders.php",
      type: "POST",
      data: { action: "delete", id: orderId },
      dataType: "json",
      success: function (data) {
        if (data.success) {
          $("#deleteLabOrderModal").modal("hide");
          showToast(
            "success",
            "Success",
            data.message || "Lab order deleted successfully"
          );
          loadLabOrders();
        } else {
          showToast(
            "error",
            "Error",
            data.message || "Failed to delete lab order"
          );
        }
      },
      error: function () {
        showToast("error", "Error", "An unexpected error occurred");
      },
    });
  }

  // Helper functions
  function getStatusBadge(status) {
    switch (status) {
      case "new":
        return '<span class="badge bg-label-primary">New</span>';
      case "in_progress":
        return '<span class="badge bg-label-info">In Progress</span>';
      case "pending_approval":
        return '<span class="badge bg-label-warning">Pending Approval</span>';
      case "ready":
        return '<span class="badge bg-label-success">Ready</span>';
      case "delivered":
        return '<span class="badge bg-label-secondary">Delivered</span>';
      case "cancelled":
        return '<span class="badge bg-label-danger">Cancelled</span>';
      default:
        return '<span class="badge bg-label-secondary">Unknown</span>';
    }
  }
  function getPriorityBadge(priority) {
    switch (priority) {
      case "high":
        return '<span class="badge bg-danger">High</span>';
      case "medium":
        return '<span class="badge bg-warning">Medium</span>';
      case "low":
        return '<span class="badge bg-success">Low</span>';
      default:
        return '<span class="badge bg-secondary">Normal</span>';
    }
  }
  function getStatusIcon(status) {
    switch (status) {
      case "new":
        return "bx-plus";
      case "in_progress":
        return "bx-time";
      case "pending_approval":
        return "bx-check-circle";
      case "ready":
        return "bx-package";
      case "delivered":
        return "bx-check-double";
      case "cancelled":
        return "bx-x";
      default:
        return "bx-info-circle";
    }
  }
  function getFileIcon(type) {
    if (type.indexOf("image") !== -1) return "bx-image";
    if (type.indexOf("pdf") !== -1) return "bx-file-pdf";
    return "bx-file";
  }
  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }
  function showToast(type, title, message) {
    // Replace this with your own toast/notification system
    alert(title + ": " + message);
  }
});
