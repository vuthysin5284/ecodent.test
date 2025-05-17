// dental-chart.js - Dental Charting System JavaScript

// Global variables
let toothData = {}; // Will store tooth data
let selectedTeeth = []; // Array to store currently selected teeth
let currentToothId = null; // Current tooth for context menu and details
let isShiftPressed = false; // Track shift key for multiple selection
let isCtrlPressed = false; // Track control key for multiple selection
let lastSelectedTooth = null; // Track last selected tooth for range selection

// Initialize when document is ready
$(document).ready(function () {
  setupEventListeners();
  updateActionButtonsState();

  // Initialize Bootstrap tooltips
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Keyboard event tracking for multi-select
  $(document).keydown(function (e) {
    if (e.key === "Shift") {
      isShiftPressed = true;
    } else if (e.key === "Control" || e.key === "Meta") {
      isCtrlPressed = true;
    } else if (e.key === "Escape") {
      hideContextMenu();
      clearSelection();
    }
  });

  $(document).keyup(function (e) {
    if (e.key === "Shift") {
      isShiftPressed = false;
    } else if (e.key === "Control" || e.key === "Meta") {
      isCtrlPressed = false;
    }
  });

  // Close context menu when clicking elsewhere
  $(document).click(function (e) {
    if (!$(e.target).closest("#toothContextMenu").length) {
      hideContextMenu();
    }
  });

  // Handle diagnosis category change
  $("#diagnosisCategory").change(function () {
    updateDiagnosisConditions($(this).val());
  });
});

// Setup event listeners
function setupEventListeners() {
  // Action buttons
  $("#addDiagnosisBtn").click(function () {
    if (selectedTeeth.length === 1) {
      openDiagnosisOffcanvas();
    } else {
      alert("Please select a single tooth to add diagnosis");
    }
  });

  $("#addTreatmentBtn").click(function () {
    if (selectedTeeth.length === 1) {
      openTreatmentOffcanvas();
    } else {
      alert("Please select a single tooth to add treatment");
    }
  });

  $("#viewDetailsBtn").click(function () {
    if (selectedTeeth.length === 1) {
      openDetailsOffcanvas();
    } else {
      alert("Please select a single tooth to view details");
    }
  });

  // Clear selection button
  $("#clearAllSelectionBtn").click(function () {
    clearSelection();
  });
}

// Tooth selection toggle
function toggleTooth(toothId) {
  const checkbox = $("#tooth" + toothId);
  const isSelected = checkbox.prop("checked");
  const container = checkbox
    .closest(".tooth-block")
    .find(".tooth-img-container");

  // Handle multi-selection with Shift key
  if (isShiftPressed && lastSelectedTooth && lastSelectedTooth !== toothId) {
    selectToothRange(lastSelectedTooth, toothId);
  }
  // Handle toggle behavior with Ctrl key
  else if (!isCtrlPressed && !isShiftPressed && isSelected) {
    // If clicking already selected tooth without modifiers, just open details
    openDetailsOffcanvas(toothId);
    return;
  } else {
    // Normal selection toggle
    checkbox.prop("checked", !isSelected);
    container.toggleClass("selected", !isSelected);

    // Update selectedTeeth array
    if (!isSelected) {
      if (!selectedTeeth.includes(toothId)) {
        selectedTeeth.push(toothId);
      }
    } else {
      selectedTeeth = selectedTeeth.filter((id) => id !== toothId);
    }
  }

  lastSelectedTooth = toothId;
  updateSelectionCount();
  updateActionButtonsState();
}

// Select teeth in a range
function selectToothRange(startTooth, endTooth) {
  // Convert tooth IDs to integers for comparison
  const start = parseInt(startTooth);
  const end = parseInt(endTooth);

  // Determine which arch (upper: 11-28, lower: 31-48)
  const isStartUpper = start >= 11 && start <= 28;
  const isEndUpper = end >= 11 && end <= 28;

  // Only process range selection within the same arch
  if (isStartUpper === isEndUpper) {
    let teethToSelect = [];

    if (isStartUpper) {
      // Logic for upper arch (11-28)
      if ((start <= 18 && end <= 18) || (start >= 21 && end >= 21)) {
        // Same quadrant
        const min = Math.min(start, end);
        const max = Math.max(start, end);
        for (let i = min; i <= max; i++) {
          if ((i >= 11 && i <= 18) || (i >= 21 && i <= 28)) {
            teethToSelect.push(i);
          }
        }
      } else {
        // Crossing quadrants (Q1 to Q2 or vice versa)
        // First quadrant (right side)
        for (let i = Math.min(start, 18); i <= 18; i++) {
          teethToSelect.push(i);
        }
        // Second quadrant (left side)
        for (let i = 21; i <= Math.max(end, 21); i++) {
          teethToSelect.push(i);
        }
      }
    } else {
      // Logic for lower arch (31-48)
      if ((start <= 38 && end <= 38) || (start >= 41 && end >= 41)) {
        // Same quadrant
        const min = Math.min(start, end);
        const max = Math.max(start, end);
        for (let i = min; i <= max; i++) {
          if ((i >= 31 && i <= 38) || (i >= 41 && i <= 48)) {
            teethToSelect.push(i);
          }
        }
      } else {
        // Crossing quadrants (Q3 to Q4 or vice versa)
        // Third quadrant (left side)
        for (let i = Math.min(start, 38); i <= 38; i++) {
          teethToSelect.push(i);
        }
        // Fourth quadrant (right side)
        for (let i = 41; i <= Math.max(end, 41); i++) {
          teethToSelect.push(i);
        }
      }
    }

    // Apply selection to the teeth
    teethToSelect.forEach((toothId) => {
      if (document.getElementById("tooth" + toothId)) {
        $("#tooth" + toothId).prop("checked", true);
        $("#tooth" + toothId)
          .closest(".tooth-block")
          .find(".tooth-img-container")
          .addClass("selected");

        if (!selectedTeeth.includes(toothId)) {
          selectedTeeth.push(toothId);
        }
      }
    });
  }
}

// Show context menu for a tooth
function showToothContext(event, toothId) {
  event.preventDefault();
  currentToothId = toothId;

  const contextMenu = $("#toothContextMenu");

  // Position the context menu at the mouse pointer
  contextMenu.css({
    display: "block",
    left: event.pageX + "px",
    top: event.pageY + "px",
  });
}

// Hide context menu
function hideContextMenu() {
  $("#toothContextMenu").hide();
}

// Clear all tooth selections
function clearSelection() {
  $(".tooth-checkbox").prop("checked", false);
  $(".tooth-img-container").removeClass("selected");
  selectedTeeth = [];
  lastSelectedTooth = null;
  updateSelectionCount();
  updateActionButtonsState();
}

// Update selection count display
function updateSelectionCount() {
  const selectionCount = selectedTeeth.length;

  if (selectionCount === 0) {
    $("#selectionInfo").text("No teeth selected");
  } else if (selectionCount === 1) {
    $("#selectionInfo").text(`Tooth #${selectedTeeth[0]} selected`);
  } else {
    $("#selectionInfo").text(`${selectionCount} teeth selected`);
  }
}

// Update action buttons disabled state
function updateActionButtonsState() {
  const selectionCount = selectedTeeth.length;
  $("#addDiagnosisBtn").prop("disabled", selectionCount !== 1);
  $("#addTreatmentBtn").prop("disabled", selectionCount !== 1);
  $("#viewDetailsBtn").prop("disabled", selectionCount !== 1);
}

// Open diagnosis offcanvas
function openDiagnosisOffcanvas() {
  if (selectedTeeth.length !== 1) return;

  currentToothId = selectedTeeth[0];
  $("#diagnosisToothId").text(currentToothId);

  // Reset form
  $("#diagnosisForm")[0].reset();
  $(".surface-selector button")
    .removeClass("btn-primary")
    .addClass("btn-outline-secondary");
  $("#selectedSurfaces").val("");

  // Show the offcanvas
  const diagnosisOffcanvas = new bootstrap.Offcanvas(
    document.getElementById("diagnosisOffcanvas")
  );
  diagnosisOffcanvas.show();
}

// Open treatment offcanvas
function openTreatmentOffcanvas() {
  if (selectedTeeth.length !== 1) return;

  currentToothId = selectedTeeth[0];
  $("#treatmentToothId").text(currentToothId);

  // Reset form
  $("#treatmentForm")[0].reset();
  $("#treatmentDate").val(getCurrentDateString());

  // Show the offcanvas
  const treatmentOffcanvas = new bootstrap.Offcanvas(
    document.getElementById("treatmentOffcanvas")
  );
  treatmentOffcanvas.show();
}

// Open details offcanvas
function openDetailsOffcanvas(toothId) {
  if (!toothId && selectedTeeth.length !== 1) return;

  currentToothId = toothId || selectedTeeth[0];
  $("#detailsToothId").text(currentToothId);

  // Populate tooth details
  loadToothDetails();

  // Show the offcanvas
  const detailsOffcanvas = new bootstrap.Offcanvas(
    document.getElementById("detailsOffcanvas")
  );
  detailsOffcanvas.show();
}

// Load tooth details
function loadToothDetails() {
  // This would typically load from the server or local data
  // For now we'll simulate data

  if (toothData[currentToothId]) {
    const data = toothData[currentToothId];

    // Create HTML for details tab
    let detailsHtml = `
            <div class="card mb-3">
                <div class="card-header">Current Status</div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="35%">Category:</th>
                            <td>${data.category || "-"}</td>
                        </tr>
                        <tr>
                            <th>Condition:</th>
                            <td>${data.condition || "-"}</td>
                        </tr>
                        <tr>
                            <th>Surfaces:</th>
                            <td>${data.surfaces || "All"}</td>
                        </tr>
                        <tr>
                            <th>Notes:</th>
                            <td>${data.notes || "-"}</td>
                        </tr>
                    </table>
                </div>
            </div>
        `;

    if (data.treatments && data.treatments.length > 0) {
      detailsHtml += `
                <div class="card">
                    <div class="card-header">Treatment Plan</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th>Treatment:</th>
                                <td>${data.treatments[0].type}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>${data.treatments[0].status}</td>
                            </tr>
                            <tr>
                                <th>Scheduled:</th>
                                <td>${data.treatments[0].date || "-"}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            `;
    }

    $("#toothDetailsContent").html(detailsHtml);
  } else {
    $("#toothDetailsContent").html(`
            <div class="alert alert-info">
                No recorded conditions or treatments for Tooth #${currentToothId}.
            </div>
        `);
  }

  // Placeholder for history tab
  $("#toothHistoryContent").html(`
        <div class="alert alert-info">
            No history available for Tooth #${currentToothId}.
        </div>
    `);

  // Placeholder for treatment tab
  $("#toothTreatmentContent").html(`
        <div class="alert alert-info">
            No treatment records available for Tooth #${currentToothId}.
        </div>
    `);
}

// Update diagnosis conditions dropdown based on category
function updateDiagnosisConditions(category) {
  const conditionSelect = $("#diagnosisCondition");
  conditionSelect
    .empty()
    .append('<option value="" selected disabled>Select condition</option>');

  if (category === "caries") {
    conditionSelect.append(`
            <option value="primary">Primary Caries</option>
            <option value="secondary">Secondary Caries</option>
            <option value="root">Root Caries</option>
        `);
  } else if (category === "periodontal") {
    conditionSelect.append(`
            <option value="gingivitis">Gingivitis</option>
            <option value="periodontitis">Periodontitis</option>
            <option value="recession">Gingival Recession</option>
        `);
  } else if (category === "endodontic") {
    conditionSelect.append(`
            <option value="pulpitis">Pulpitis</option>
            <option value="necrosis">Pulp Necrosis</option>
            <option value="abscess">Periapical Abscess</option>
        `);
  } else if (category === "surgical") {
    conditionSelect.append(`
            <option value="extracted">Extracted</option>
            <option value="impacted">Impacted</option>
            <option value="fracture">Fracture</option>
        `);
  } else if (category === "other") {
    conditionSelect.append(`
            <option value="abrasion">Abrasion</option>
            <option value="erosion">Erosion</option>
            <option value="sensitivity">Sensitivity</option>
            <option value="malalignment">Malalignment</option>
        `);
  }
}

// Toggle surface selection
function toggleSurface(button) {
  $(button).toggleClass("btn-outline-secondary btn-primary");
  updateSelectedSurfaces();
}

// Toggle all surfaces
function toggleAllSurfaces() {
  if (
    $(".surface-selector button.btn-primary").length ===
    $(".surface-selector button").length
  ) {
    // All are selected, deselect all
    $(".surface-selector button")
      .removeClass("btn-primary")
      .addClass("btn-outline-secondary");
  } else {
    // Not all selected, select all
    $(".surface-selector button")
      .removeClass("btn-outline-secondary")
      .addClass("btn-primary");
  }

  updateSelectedSurfaces();
}

// Update selected surfaces hidden input
function updateSelectedSurfaces() {
  const selectedSurfaces = [];

  $(".surface-selector button.btn-primary").each(function () {
    selectedSurfaces.push($(this).data("surface"));
  });

  $("#selectedSurfaces").val(selectedSurfaces.join(","));
}

// Save diagnosis
function saveDiagnosis() {
  const toothId = currentToothId;
  const category = $("#diagnosisCategory").val();
  const condition = $("#diagnosisCondition").val();
  const surfaces = $("#selectedSurfaces").val() || "All";
  const notes = $("#diagnosisNotes").val();

  // Validation
  if (!category || !condition) {
    alert("Please select a category and condition");
    return;
  }

  // Save to toothData
  toothData[toothId] = {
    category: $("#diagnosisCategory option:selected").text(),
    condition: $("#diagnosisCondition option:selected").text(),
    surfaces: surfaces,
    notes: notes,
    date: getCurrentDateString(),
  };

  // Update visual indicator
  updateToothVisualIndicator(toothId);

  // Close offcanvas
  const diagnosisOffcanvas = bootstrap.Offcanvas.getInstance(
    document.getElementById("diagnosisOffcanvas")
  );
  diagnosisOffcanvas.hide();

  // Show success message
  alert(`Diagnosis added for tooth #${toothId}`);
}

// Save treatment plan
function saveTreatmentPlan() {
  const toothId = currentToothId;
  const treatmentType = $("#treatmentType").val();
  const priority = $("#treatmentPriority").val();
  const scheduledDate = $("#treatmentDate").val();
  const estimatedCost = $("#estimatedCost").val();
  const notes = $("#treatmentNotes").val();

  // Validation
  if (!treatmentType) {
    alert("Please select a treatment type");
    return;
  }

  // Initialize tooth data if it doesn't exist
  if (!toothData[toothId]) {
    toothData[toothId] = {
      treatments: [],
    };
  } else if (!toothData[toothId].treatments) {
    toothData[toothId].treatments = [];
  }

  // Add treatment to tooth data
  toothData[toothId].treatments.push({
    type: $("#treatmentType option:selected").text(),
    priority: priority,
    date: scheduledDate,
    cost: estimatedCost,
    notes: notes,
    status: "Planned",
  });

  // Update visual indicator
  updateToothVisualIndicator(toothId);

  // Close offcanvas
  const treatmentOffcanvas = bootstrap.Offcanvas.getInstance(
    document.getElementById("treatmentOffcanvas")
  );
  treatmentOffcanvas.hide();

  // Show success message
  alert(`Treatment plan added for tooth #${toothId}`);
}

// Clear tooth status
function clearToothStatus() {
  const toothId = currentToothId;

  // Remove data for this tooth
  if (toothData[toothId]) {
    delete toothData[toothId];
  }

  // Remove visual indicators
  const toothBlock = $("#tooth" + toothId).closest(".tooth-block");
  toothBlock.find(".tooth-status-indicator").remove();
  toothBlock
    .find(".tooth-img-container")
    .removeClass("has-condition bg-diagnosis bg-treatment bg-completed");

  // Hide context menu
  hideContextMenu();

  // Show success message
  alert(`Status cleared for tooth #${toothId}`);
}

// Update tooth visual indicator
function updateToothVisualIndicator(toothId) {
  const toothBlock = $("#tooth" + toothId).closest(".tooth-block");
  const container = toothBlock.find(".tooth-img-container");

  // Remove any existing indicators
  toothBlock.find(".tooth-status-indicator").remove();
  container.removeClass("has-condition bg-diagnosis bg-treatment bg-completed");

  // Add new indicator if there's tooth data
  if (toothData[toothId]) {
    const data = toothData[toothId];

    // Set has-condition class
    container.addClass("has-condition");

    // Check if tooth has completed treatment
    const hasCompletedTreatment =
      data.treatments && data.treatments.some((t) => t.status === "Completed");

    // Check if tooth has treatment plan
    const hasTreatmentPlan =
      data.treatments && data.treatments.length > 0 && !hasCompletedTreatment;

    // Apply color coding based on status
    if (hasCompletedTreatment) {
      // Completed treatment (success color)
      container.addClass("bg-completed");

      // Add indicator icon
      toothBlock.append(`
                <div class="tooth-status-indicator treated">
                    <i class="bi bi-check-circle"></i>
                </div>
            `);
    } else if (hasTreatmentPlan) {
      // Treatment planned (primary color)
      container.addClass("bg-treatment");

      // Add indicator icon
      toothBlock.append(`
                <div class="tooth-status-indicator treatment">
                    <i class="bi bi-calendar-check"></i>
                </div>
            `);
    } else if (data.category) {
      // Has diagnosis (danger color)
      container.addClass("bg-diagnosis");

      // Add indicator icon
      toothBlock.append(`
                <div class="tooth-status-indicator caries">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
            `);
    }
  }

  // Make sure selected state is maintained
  if (selectedTeeth.includes(parseInt(toothId))) {
    container.addClass("selected");
  }
}

// Quick selection functions
function selectFullArch() {
  clearSelection();

  const allTeethList = [
    11, 12, 13, 14, 15, 16, 17, 18, 21, 22, 23, 24, 25, 26, 27, 28, 31, 32, 33,
    34, 35, 36, 37, 38, 41, 42, 43, 44, 45, 46, 47, 48,
  ];

  selectTeethFromList(allTeethList);
}

function selectUpperArch() {
  clearSelection();

  const upperTeethList = [
    11, 12, 13, 14, 15, 16, 17, 18, 21, 22, 23, 24, 25, 26, 27, 28,
  ];

  selectTeethFromList(upperTeethList);
}

function selectLowerArch() {
  clearSelection();

  const lowerTeethList = [
    31, 32, 33, 34, 35, 36, 37, 38, 41, 42, 43, 44, 45, 46, 47, 48,
  ];

  selectTeethFromList(lowerTeethList);
}

function selectQuadrant(quadrant) {
  clearSelection();

  let teethList = [];

  switch (quadrant) {
    case "Q1":
      teethList = [11, 12, 13, 14, 15, 16, 17, 18];
      break;
    case "Q2":
      teethList = [21, 22, 23, 24, 25, 26, 27, 28];
      break;
    case "Q3":
      teethList = [31, 32, 33, 34, 35, 36, 37, 38];
      break;
    case "Q4":
      teethList = [41, 42, 43, 44, 45, 46, 47, 48];
      break;
  }

  selectTeethFromList(teethList);
}

function selectAnteriorTeeth() {
  clearSelection();

  const anteriorTeeth = [13, 12, 11, 21, 22, 23, 33, 32, 31, 41, 42, 43];

  selectTeethFromList(anteriorTeeth);
}

function selectPosteriorTeeth() {
  clearSelection();

  const posteriorTeeth = [
    18, 17, 16, 15, 14, 24, 25, 26, 27, 28, 38, 37, 36, 35, 34, 44, 45, 46, 47,
    48,
  ];

  selectTeethFromList(posteriorTeeth);
}

function selectMolarsOnly() {
  clearSelection();

  const molars = [18, 17, 16, 26, 27, 28, 38, 37, 36, 46, 47, 48];

  selectTeethFromList(molars);
}

function selectLeftSide() {
  clearSelection();

  const leftSideTeeth = [
    28, 27, 26, 25, 24, 23, 22, 21, 38, 37, 36, 35, 34, 33, 32, 31,
  ];

  selectTeethFromList(leftSideTeeth);
}

function selectRightSide() {
  clearSelection();

  const rightSideTeeth = [
    18, 17, 16, 15, 14, 13, 12, 11, 48, 47, 46, 45, 44, 43, 42, 41,
  ];

  selectTeethFromList(rightSideTeeth);
}

function selectTeethByStatus(status) {
  clearSelection();

  const toSelect = [];

  if (status === "needsAttention") {
    // Select teeth with diagnosis but no treatment
    for (const toothId in toothData) {
      const data = toothData[toothId];
      if (data.category && (!data.treatments || data.treatments.length === 0)) {
        toSelect.push(parseInt(toothId));
      }
    }
  }

  selectTeethFromList(toSelect);
}

// Select teeth from a list
function selectTeethFromList(teethList) {
  teethList.forEach((toothId) => {
    if ($("#tooth" + toothId).length) {
      $("#tooth" + toothId).prop("checked", true);
      $("#tooth" + toothId)
        .closest(".tooth-block")
        .find(".tooth-img-container")
        .addClass("selected");

      if (!selectedTeeth.includes(toothId)) {
        selectedTeeth.push(toothId);
      }
    }
  });

  updateSelectionCount();
  updateActionButtonsState();
}

// Helper to get current date string in YYYY-MM-DD format
function getCurrentDateString() {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, "0");
  const day = String(today.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
}
