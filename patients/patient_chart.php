<?php 
	session_start();
	$page = 'Treatment Service';
	$log = $_SESSION['uid'];
	$lang = $_SESSION['lang'];
	include_once("../inc/config.php");
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
      $page = 'General Setting';
      $clinic = 'Asia Dental Clinic';
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
    <link rel="stylesheet" href="../assets/vendor/libs/dropzone/dropzone.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="../assets/dental-chart.css">
		<link rel="stylesheet" href="../assets/css/dental-chart.css">
  </head>
  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include_once('../inc/navigation_menu.php'); ?>
        <div class="layout-page">
          <?php include_once('../inc/navbar.php'); ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              	<!-- Heading --> 
          		<?php include_once('../inc/patient_menu.php'); ?>
			</div>

			<!-- Treatment Category Row (with images instead of bx icons) -->
			<div class="card mb-3 bg-transparent shadow-none">
				<div class="card-body p-2">
					<div class="d-flex gap-2 justify-content-center align-items-center flex-wrap">
						<a href="#" class="d-block btn btn-label-primary">
							<img src="../assets/svg/treatment/filling.svg" alt="Filling" width="24" height="24">
							<div class="text-sm">Filling</div>
						</a>
						<a href="#" class="d-block btn btn-label-primary">
							<img src="../assets/svg/treatment/crown.svg" alt="Crown" width="24" height="24">
							<div class="text-sm">Crown</div>
						</a>
						<a href="#" class="d-block btn btn-label-primary">
							<img src="../assets/svg/treatment/root-canal.svg" alt="Root Canal" width="24" height="24">
							<div class="text-sm">Root Canal</div>
						</a>
						<a href="#" class="d-block btn btn-label-primary">
							<img src="../assets/svg/treatment/extraction.svg" alt="Extraction" width="24" height="24">
							<div class="text-sm">Extraction</div>
						</a>
						<a href="#" class="d-block btn btn-label-primary">
							<img src="../assets/svg/treatment/implant.svg" alt="Implant" width="24" height="24">
							<div class="text-sm">Implant</div>
						</a>
						<a href="#" class="d-block btn btn-label-primary">
							<img src="../assets/svg/treatment/veneer.svg" alt="Veneer" width="24" height="24">
							<div class="text-sm">Veneer</div>
						</a>
						<a href="#" class="d-block btn btn-label-primary">
							<img src="../assets/svg/treatment/other.svg" alt="Other" width="24" height="24">
							<div class="text-sm">Other</div>
						</a>
					</div>
				</div>
			</div>

              <!-- Content -->
              <div class="card mb-4">
					<div class="card-header">
						<div class="mb-3">
							<div class="btn-group" role="group" aria-label="Tooth Type Switch">
									<button type="button" id="adultTeethBtn" class="btn btn-primary active">Adult Teeth</button>
									<button type="button" id="childTeethBtn" class="btn btn-outline-primary">Child Teeth</button>
							</div>
						</div>
					</div>
					
					<div class="card-body">
						<h5>Upper Jaw</h5>
						<div class="dental-chart" id="upperJaw">
								<!-- Upper teeth will be dynamically loaded here -->
						</div>
						<h5>Lower Jaw</h5>
						<div class="dental-chart" id="lowerJaw">
							<!-- Lower teeth will be dynamically loaded here -->
						</div>
						<div class="mt-3">
							<button id="clearSelectionBtn" class="btn btn-outline-secondary">Clear Selection</button>
							<button id="treatmentPlanBtn" class="btn btn-primary" disabled>Create Treatment Plan</button>
						</div>
					</div>
				</div>
              <!-- End Content -->
            </div>

			<!-- Offcanvas for Treatment Plan -->
			<div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 80%;" tabindex="-1" id="treatmentOffcanvas" 
                aria-labelledby="treatmentOffcanvasLabel">
				<div class="offcanvas-header">
						<h5 class="offcanvas-title" id="treatmentOffcanvasLabel">Create Treatment Plan</h5>
						<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body">
						<div class="mb-3">
								<h6>Selected Teeth: <span id="selectedTeethDisplay"></span></h6>
						</div>
						
						<!-- Search bar for treatment items -->
						<div class="mb-3">
								<div class="input-group">
										<span class="input-group-text"><i class="bi bi-search"></i></span>
										<input type="text" class="form-control" id="treatmentSearchInput" placeholder="Search treatment services...">
								</div>
						</div>
						
						<!-- Category tabs -->
						<div class="mb-3">
								<ul class="nav nav-pills" id="categoryTabs" role="tablist">
										<!-- Categories will be dynamically added here -->
								</ul>
						</div>
						
						<!-- Treatment items container -->
						<div class="tab-content mb-3" id="categoryTabsContent">
								<!-- Treatment items for each category will be loaded here -->
						</div>
						
						<!-- Selected treatment details -->
						<div id="selectedTreatmentDetails" class="mb-3" style="display: none;">
								<h6 class="border-bottom pb-2">Selected Treatment</h6>
								<div id="selectedServiceInfo" class="mb-3"></div>
								
								<div id="materialSelectionArea">
										<h6>Select Material</h6>
										<div id="materialList" class="list-group mb-3">
												<!-- Materials will be loaded here -->
										</div>
								</div>
						</div>
						
						<!-- Treatment summary -->
						<div id="treatmentSummary" class="card p-3 mb-3" style="display: none;">
								<h6 class="border-bottom pb-2 mb-3">Treatment Summary</h6>
								<div id="summaryContent"></div>
								<div class="mt-3">
										<button id="editSelectionBtn" class="btn btn-outline-secondary">Edit Selection</button>
										<button id="saveTreatmentBtn" class="btn btn-success">Save Treatment Plan</button>
								</div>
						</div>
				</div>
			</div>

            <?php include_once('../inc/footage.php'); ?>
          </div>
        </div>
      </div>
    </div>
  </body>
  <foot>
    <?php include_once('../inc/footer.php'); ?>
    <script src="../assets/vendor/libs/dropzone/dropzone.js"></script>
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <!-- <script src="../assets/js/app-ecommerce-product-add.js"></script> -->
    <script src="../assets/js/ui-cards-analytics.js"></script>
    <!-- <script src="../services/patients/create.js"></script>  -->
    <!-- <script src="../assets/dental-chart.js"></script> -->
     <script type="text/javascript">
        window.onload = function(){
            var activeMenu = document.getElementById("Patients");
            activeMenu.classList.add("open");
            activeMenu.classList.add("active"); 

            var activeTabMenu = document.getElementById("patient-chart");
            activeTabMenu.classList.add("active");
        };
    </script>
    <script>
			$(document).ready(function() {
                
				let currentToothType = 'adult'; // 'adult' or 'child'
				// Store the currently selected teeth
				let selectedTeeth = [];
				
				// Store the current treatment plan details
				let currentTreatment = {
						patient_id: 1, // Default patient ID
						category_id: null,
						category_name: '',
						service_id: null,
						service_name: '',
						material_id: null,
						material_name: ''
				};
				// Switch button handlers
				$('#adultTeethBtn').on('click', function() {
						currentToothType = 'adult';
						$(this).addClass('active').removeClass('btn-outline-primary').addClass('btn-primary');
						$('#childTeethBtn').removeClass('active').removeClass('btn-primary').addClass('btn-outline-primary');
						loadTeeth();
				});

				$('#childTeethBtn').on('click', function() {
						currentToothType = 'child';
						$(this).addClass('active').removeClass('btn-outline-primary').addClass('btn-primary');
						$('#adultTeethBtn').removeClass('active').removeClass('btn-primary').addClass('btn-outline-primary');
						loadTeeth();
				});
				
				loadTeeth();
			
				// Function to load teeth from database
				function loadTeeth() {
					$.ajax({
						url: '../api/v1/clinic/dental-chart.php?action=get_teeth',
						type: 'GET',
						data: { 
							patient_id: currentTreatment.patient_id,
							type: currentToothType
						},
						dataType: 'json',
						success: function(data) {
							renderTeeth(data.teeth, data.treatments);
						},
						error: function(xhr, status, error) {
							console.error('Error loading teeth:', error);
							alert('Failed to load teeth: ' + error);
						}
					});
				}

				// Function to render teeth on the chart
				function renderTeeth(teeth, treatments) {
					const upperJaw = $('#upperJaw');
					const lowerJaw = $('#lowerJaw');
					upperJaw.empty();
					lowerJaw.empty();
					// Add teeth to the chart
					teeth.forEach(function(tooth) {
						const toothElement = $('<div>')
							.addClass('tooth')
							.attr('data-tooth-number', tooth.tooth_number)
							.html(`
								<img src="../assets/img/teeth/${tooth.image_url}" alt="Tooth ${tooth.tooth_number}">
								<div class="tooth-number">${tooth.tooth_number}</div>
							`);
						// Add treatment status classes if applicable
						if (treatments && treatments[tooth.tooth_number]) {
							toothElement.addClass(treatments[tooth.tooth_number]);
						}
						// Place tooth in the correct jaw
						if (tooth.tooth_number >= 11 && tooth.tooth_number <= 28) {
							// Upper jaw
							if (tooth.tooth_number <= 18) {
								// Upper right (in reverse order)
								upperJaw.prepend(toothElement);
							} else {
								// Upper left
								upperJaw.append(toothElement);
							}
						} else {
							// Lower jaw
							if (tooth.tooth_number >= 41) {
								// Lower right (in reverse order)
								lowerJaw.prepend(toothElement);
							} else {
								// Lower left
								lowerJaw.append(toothElement);
							}
						}
					});
					// Attach click handler to teeth
					$('.tooth').on('click', function() {
						const toothNumber = parseInt($(this).attr('data-tooth-number'));
						toggleToothSelection(toothNumber, $(this));
					});
				}

				// Function to toggle tooth selection
				function toggleToothSelection(toothNumber, toothElement) {
						const index = selectedTeeth.indexOf(toothNumber);
						
						if (index === -1) {
								// Add to selection
								selectedTeeth.push(toothNumber);
								toothElement.addClass('selected');
						} else {
								// Remove from selection
								selectedTeeth.splice(index, 1);
								toothElement.removeClass('selected');
						}
						// Update the treatment button state
						$('#treatmentPlanBtn').prop('disabled', selectedTeeth.length === 0);
				}
    
				// Clear selection button
				$('#clearSelectionBtn').on('click', function() {
						selectedTeeth = [];
						$('.tooth').removeClass('selected');
						$('#treatmentPlanBtn').prop('disabled', true);
				});

				// Treatment plan button
    $('#treatmentPlanBtn').on('click', function() {
        // Reset treatment details
        currentTreatment.category_id = null;
        currentTreatment.service_id = null;
        currentTreatment.material_id = null;
        
        // Show selected teeth in the offcanvas
        $('#selectedTeethDisplay').text(selectedTeeth.join(', '));
        
        // Reset the treatment steps
        $('#stepCategory').show();
        $('#stepService, #stepMaterial, #stepSummary').hide();
        
        // Load treatment categories
        loadTreatmentCategories();
        
        // Show the offcanvas
        const offcanvas = new bootstrap.Offcanvas(document.getElementById('treatmentOffcanvas'));
        offcanvas.show();
    });
    
    // Load treatment categories
    // Load treatment categories and setup the offcanvas UI
    function loadTreatmentCategories() {
        $.ajax({
            url: '../api/v1/clinic/dental-chart.php?action=get_treatment_categories',
            type: 'GET',
            dataType: 'json',
            success: function(categories) {
                const categoryTabs = $('#categoryTabs');
                const categoryTabsContent = $('#categoryTabsContent');
                
                categoryTabs.empty();
                categoryTabsContent.empty();
                
                // Add each category as a tab
                categories.forEach(function(category, index) {
                    // Create the tab
                    categoryTabs.append(`
                        <li class="nav-item" role="presentation">
                            <button class="nav-link ${index === 0 ? 'active' : ''}" 
                                    id="category-${category.id}-tab" 
                                    data-bs-toggle="pill" 
                                    data-bs-target="#category-${category.id}-content" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="category-${category.id}-content" 
                                    aria-selected="${index === 0 ? 'true' : 'false'}"
                                    data-category-id="${category.id}"
                                    data-category-name="${category.category_name}">
                                ${category.category_name}
                            </button>
                        </li>
                    `);
                    
                    // Create the tab content container
                    categoryTabsContent.append(`
                        <div class="tab-pane fade ${index === 0 ? 'show active' : ''}" 
                             id="category-${category.id}-content" 
                             role="tabpanel" 
                             aria-labelledby="category-${category.id}-tab">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6>${category.category_name} Services</h6>
                            </div>
                            <div class="list-group service-list" data-category-id="${category.id}">
                                <!-- Services will be loaded here -->
                                <div class="text-center p-3">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                    
                    // Load services for the first category automatically
                    if (index === 0) {
                        loadTreatmentServices(category.id);
                        currentTreatment.category_id = category.id;
                        currentTreatment.category_name = category.category_name;
                    }
                });
                
                // Add click event to category tabs to load services when a tab is clicked
                $('.nav-link').on('click', function() {
                    const categoryId = $(this).data('category-id');
                    const categoryName = $(this).data('category-name');
                    
                    // Update current treatment
                    currentTreatment.category_id = categoryId;
                    currentTreatment.category_name = categoryName;
                    
                    // Check if services are already loaded
                    const serviceList = $(`.service-list[data-category-id="${categoryId}"]`);
                    if (serviceList.find('.service-item').length === 0) {
                        loadTreatmentServices(categoryId);
                    }
                    
                    // Reset the selection if the category changes
                    $('#selectedTreatmentDetails').hide();
                    $('#treatmentSummary').hide();
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading treatment categories:', error);
                alert('Failed to load treatment categories: ' + error);
            }
        });
    	}

			// Load treatment services for a specific category
    function loadTreatmentServices(categoryId) {
        $.ajax({
            url: '../api/v1/clinic/dental-chart.php?action=get_treatment_services',
            type: 'GET',
            data: { category_id: categoryId },
            dataType: 'json',
            success: function(services) {
                const serviceList = $(`.service-list[data-category-id="${categoryId}"]`);
                serviceList.empty();
                
                if (services.length === 0) {
                    serviceList.append('<div class="alert alert-info">No services available for this category.</div>');
                } else {
                    services.forEach(function(service) {
                        serviceList.append(`
                            <button class="list-group-item list-group-item-action service-item p-4" 
                                    data-service-id="${service.id}" 
                                    data-service-name="${service.service_name}"
                                    data-category-id="${categoryId}">
                                ${service.service_name}
                            </button>
                        `);
                    });
                    
                    // Add click handler for service items
                    $('.service-item').on('click', function() {
                        const serviceId = $(this).data('service-id');
                        const serviceName = $(this).data('service-name');
                        const categoryId = $(this).data('category-id');
                        
                        // Update current treatment
                        currentTreatment.service_id = serviceId;
                        currentTreatment.service_name = serviceName;
                        
                        // Show selected service information
                        $('#selectedServiceInfo').html(`
                            <p><strong>Selected Service:</strong> ${currentTreatment.category_name} - ${serviceName}</p>
                        `);
                        
                        // Show the selected treatment details section
                        $('#selectedTreatmentDetails').show();
                        
                        // Hide other services and only show the selected one
                        $('.service-item').hide();
                        $(this).show();
                        
                        // Load materials for the selected service
                        loadTreatmentMaterials(serviceId);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading treatment services:', error);
                alert('Failed to load treatment services: ' + error);
            }
        });
    }
    
    // Load treatment materials
    function loadTreatmentMaterials(serviceId) {
        $.ajax({
            url: '../api/v1/clinic/dental-chart.php?action=get_treatment_materials',
            type: 'GET',
            data: { service_id: serviceId },
            dataType: 'json',
            success: function(materials) {
                const materialList = $('#materialList');
                materialList.empty();
                
                if (materials.length === 0) {
                    materialList.append('<div class="alert alert-info">No materials available for this service.</div>');
                    
                    // If no materials available, show treatment summary directly
                    currentTreatment.material_id = null;
                    currentTreatment.material_name = 'N/A';
                    
                    // Show the summary
                    showTreatmentSummary();
                } else {
                    materials.forEach(function(material) {
                        materialList.append(`
                            <button class="list-group-item list-group-item-action material-item p-4" 
                                    data-material-id="${material.id}" 
                                    data-material-name="${material.material_name}">
                                ${material.material_name}
                            </button>
                        `);
                    });
                    
                    // Add click handler for material items
                    $('.material-item').on('click', function() {
                        const materialId = $(this).data('material-id');
                        const materialName = $(this).data('material-name');
                        
                        // Update current treatment
                        currentTreatment.material_id = materialId;
                        currentTreatment.material_name = materialName;
                        
                        // Show the summary
                        showTreatmentSummary();
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading treatment materials:', error);
                alert('Failed to load treatment materials: ' + error);
            }
        });
    }
    
    function loadTreatmentMaterials(serviceId) {
        $.ajax({
            url: 'get_treatment_materials.php',
            type: 'GET',
            data: { service_id: serviceId },
            dataType: 'json',
            success: function(materials) {
                const materialList = $('#materialList');
                materialList.empty();
                
                if (materials.length === 0) {
                    materialList.append('<div class="alert alert-info">No materials available for this service.</div>');
                    
                    // If no materials available, show treatment summary directly
                    currentTreatment.material_id = null;
                    currentTreatment.material_name = 'N/A';
                    
                    // Show the summary
                    showTreatmentSummary();
                } else {
                    materials.forEach(function(material) {
                        materialList.append(`
                            <button class="list-group-item list-group-item-action material-item p-4" 
                                    data-material-id="${material.id}" 
                                    data-material-name="${material.material_name}">
                                ${material.material_name}
                            </button>
                        `);
                    });
                    
                    // Add click handler for material items
                    $('.material-item').on('click', function() {
                        const materialId = $(this).data('material-id');
                        const materialName = $(this).data('material-name');
                        
                        // Update current treatment
                        currentTreatment.material_id = materialId;
                        currentTreatment.material_name = materialName;
                        
                        // Show the summary
                        showTreatmentSummary();
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading treatment materials:', error);
                alert('Failed to load treatment materials: ' + error);
            }
        });
    }
    });
		</script>
  </foot>
</html>
