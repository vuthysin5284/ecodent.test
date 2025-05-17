<?php
session_start();
$log = $_SESSION['uid'];
if (!isset($log)) { header('HTTP/1.1 401 Unauthorized'); exit; }
include_once '../../../inc/config.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
  case 'list':
    getMedicalHistories();
    break;
  case 'get':
    getMedicalHistory();
    break;
  case 'create':
    createMedicalHistory();
    break;
  case 'update':
    updateMedicalHistory();
    break;
  case 'delete':
    deleteMedicalHistory();
    break;
  default:
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

function getMedicalHistories() {
  global $CON;
  $sql = "SELECT mh.*, cust_fname AS patient_name
          FROM medical_history mh
          LEFT JOIN tbl_customer p ON mh.patient_id = p.id
          ORDER BY mh.date DESC";
  $result = $CON->query($sql);
  $histories = [];
  while ($row = $result->fetch_assoc()) $histories[] = $row;
  echo json_encode(['success' => true, 'histories' => $histories]);
}

function getMedicalHistory() {
  global $CON;
  $id = intval($_GET['id'] ?? 0);
  if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    return;
  }
  $sql = "SELECT * FROM medical_history WHERE id = ?";
  $stmt = $CON->prepare($sql);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($history = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'history' => $history]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Not found']);
  }
}

function createMedicalHistory() {
  global $CON;
  $patientId = intval($_POST['patientId']);
  $date = $_POST['date'];
  $condition = $_POST['condition'];
  $allergies = $_POST['allergies'];
  $medications = $_POST['medications'];
  $notes = $_POST['notes'];
  $sql = "INSERT INTO medical_history (patient_id, date, condition, allergies, medications, notes, created_by, created_at)
          VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
  $stmt = $CON->prepare($sql);
  $stmt->bind_param('issssss', $patientId, $date, $condition, $allergies, $medications, $notes, $_SESSION['uid']);
  if ($stmt->execute()) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
  }
}

function updateMedicalHistory() {
  global $CON;
  $id = intval($_POST['historyId']);
  $patientId = intval($_POST['patientId']);
  $date = $_POST['date'];
  $condition = $_POST['condition'];
  $allergies = $_POST['allergies'];
  $medications = $_POST['medications'];
  $notes = $_POST['notes'];
  $sql = "UPDATE medical_history SET patient_id=?, date=?, condition=?, allergies=?, medications=?, notes=?, updated_by=?, updated_at=NOW() WHERE id=?";
  $stmt = $CON->prepare($sql);
  $stmt->bind_param('issssssi', $patientId, $date, $condition, $allergies, $medications, $notes, $_SESSION['uid'], $id);
  if ($stmt->execute()) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
  }
}

function deleteMedicalHistory() {
  global $CON;
  $id = intval($_POST['id']);
  $sql = "DELETE FROM medical_history WHERE id=?";
  $stmt = $CON->prepare($sql);
  $stmt->bind_param('i', $id);
  if ($stmt->execute()) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
  }
}
?>
