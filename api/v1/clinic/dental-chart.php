<?php
 include_once '../../../inc/config.php';

  $action = $_GET['action'] ?? '';
  switch ($action) {
    case 'get_teeth':
      getTeeth();
      break;
    case 'get_treatment_categories':
      getTreatmentCategories();
      break;
    case 'get_treatment_services':
      getTreatmentServices();
      break;
    case 'get_treatment_materials':
      getTreatmentMaterials();
      break;
    default:
      http_response_code(400);
      echo json_encode(['success' => false, 'message' => 'Invalid action']);
      break;
    }

  // Get all teeth information from the database
  function getTeeth(){
    global $CON;

    $type = isset($_GET['type']) ? $_GET['type'] : 'adult';
    if ($type === 'adult') {
        $sql = "SELECT * FROM tbl_tooth_number WHERE tooth_number BETWEEN 11 AND 48";
    } else if ($type === 'child') {
        $sql = "SELECT * FROM tbl_tooth_number WHERE tooth_number BETWEEN 51 AND 85";
    } else {
        $sql = "SELECT * FROM tbl_tooth_number";
    }

    // Exclude special items (assuming their numbers are not numeric)
    $sql .= " AND tooth_number NOT IN ('UP', 'LA', 'FA') ORDER BY tooth_number";
    $result = $CON->query($sql);

    $teeth = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $teeth[] = $row;
        }
    }
    
    // Get treatment plans to highlight teeth with planned or completed treatments
    $sql = "SELECT tp.tooth_number, tp.status FROM tbl_treatment_plan1 tp 
            WHERE tp.patient_id = ?";
    $stmt = $CON->prepare($sql);
    $patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : 1; // Default to patient 1
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $treatments = array();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        if (!isset($treatments[$row['tooth_number']])) {
            $treatments[$row['tooth_number']] = $row['status'];
        } else if ($treatments[$row['tooth_number']] == 'planned' && $row['status'] == 'completed') {
            // If at least one treatment is completed, show as completed
            $treatments[$row['tooth_number']] = 'completed';
        }
    }
    header('Content-Type: application/json');
    echo json_encode(array(
        'teeth' => $teeth,
        'treatments' => $treatments
    ));
    $CON->close();
  }

  function getTreatmentCategories(){
    global $CON;
    $sql = "SELECT * FROM tbl_treatment_category ORDER BY category_name";
    $result = $CON->query($sql);

    $categories = array();
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $categories[] = $row;
      }
    }
    header('Content-Type: application/json');
    echo json_encode($categories);
    $CON->close();
  }

  function getTreatmentServices(){
    global $CON;
    if (!isset($_GET['category_id'])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(array('error' => 'Category ID is required'));
        exit;
    }
    $category_id = $_GET['category_id'];
    // Get services for the specified category
    $sql = "SELECT * FROM tbl_treatment_service WHERE category_id = ? ORDER BY service_name";
    $stmt = $CON->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $services = array();
    while($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($services);

    $CON->close();

   
  }

  function getTreatmentMaterials(){
    if (!isset($_GET['service_id'])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(array('error' => 'Service ID is required'));
        exit;
    }

    $service_id = $_GET['service_id'];

    // Get materials for the specified service
    $sql = "SELECT * FROM tbl_treatment_material WHERE service_id = ? ORDER BY material_name";
    $stmt = $CON->prepare($sql);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $materials = array();
    while($row = $result->fetch_assoc()) {
        $materials[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($materials);

    $CON->close();
  }
?>
