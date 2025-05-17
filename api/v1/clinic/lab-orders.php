<?php
session_start();
$log = $_SESSION['uid'];
if (!isset($log)) {
  header('HTTP/1.1 401 Unauthorized');
  exit;
}
include_once '../../../inc/config.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
  case 'list':
    getLabOrders();
    break;
  case 'get':
    getLabOrder();
    break;
  case 'create':
    createLabOrder();
    break;
  case 'update':
    updateLabOrder();
    break;
  case 'delete':
    deleteLabOrder();
    break;
  case 'updateStatus':
    updateOrderStatus();
    break;
  default:
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

// List all lab orders with filters
function getLabOrders() {
  global $CON;
  $sql = "SELECT lo.*, 
            p.cust_fname AS patient_name
            d.staff_fname AS doctor_name
          FROM lab_orders lo
          LEFT JOIN tbl_customer p ON lo.patient_id = p.id
          LEFT JOIN tbl_staff d ON lo.doctor_id = d.id
          WHERE 1=1";
  $params = [];
  $types = '';

  if (!empty($_GET['dateRange'])) {
    $dates = explode(' - ', $_GET['dateRange']);
    if (count($dates) == 2) {
      $startDate = date('Y-m-d', strtotime($dates[0]));
      $endDate = date('Y-m-d', strtotime($dates[1]));
      $sql .= " AND (lo.request_date BETWEEN ? AND ? OR lo.due_date BETWEEN ? AND ?)";
      $params = array_merge($params, [$startDate, $endDate, $startDate, $endDate]);
      $types .= 'ssss';
    }
  }
  if (!empty($_GET['status'])) {
    $sql .= " AND lo.status = ?";
    $params[] = $_GET['status'];
    $types .= 's';
  }
  if (!empty($_GET['priority'])) {
    $sql .= " AND lo.priority = ?";
    $params[] = $_GET['priority'];
    $types .= 's';
  }
  if (!empty($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $sql .= " AND (lo.id LIKE ? OR p.first_name LIKE ? OR p.last_name LIKE ? OR d.first_name LIKE ? OR d.last_name LIKE ? OR lo.work_type LIKE ?)";
    $params = array_merge($params, [$search, $search, $search, $search, $search, $search]);
    $types .= 'ssssss';
  }
  $sql .= " ORDER BY lo.request_date DESC";
  // $stmt = $CON->prepare($sql);
  // if (!empty($params)) $stmt->bind_param($types, ...$params);
  // $stmt->execute();
  // $result = $stmt->get_result();
  // $orders = [];
  // while ($row = $result->fetch_assoc()) $orders[] = $row;
  // echo json_encode(['success' => true, 'orders' => $orders]);
  echo json_decode($sql);
}

// Get a single lab order with timeline and attachments
function getLabOrder() {
  global $CON;
  $id = intval($_GET['id'] ?? 0);
  if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid lab order ID']);
    return;
  }
  $sql = "SELECT lo.*, 
              p.cust_fname AS patient_name
              d.staff_fname AS doctor_name
          FROM lab_orders lo
          LEFT JOIN tbl_customer p ON lo.patient_id = p.id
          LEFT JOIN tbl_staff d ON lo.doctor_id = d.id
          WHERE lo.id = ?";
  $stmt = $CON->prepare($sql);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($order = $result->fetch_assoc()) {
    // Timeline
    $timeline = [];
    $tl = $CON->prepare("SELECT * FROM lab_order_timeline WHERE order_id = ? ORDER BY created_at ASC");
    $tl->bind_param('i', $id);
    $tl->execute();
    $tlres = $tl->get_result();
    while ($row = $tlres->fetch_assoc()) $timeline[] = $row;
    $order['timeline'] = $timeline;
    // Attachments
    $attachments = [];
    $att = $CON->prepare("SELECT * FROM lab_order_attachments WHERE order_id = ?");
    $att->bind_param('i', $id);
    $att->execute();
    $attres = $att->get_result();
    while ($row = $attres->fetch_assoc()) {
      $row['url'] = '../' . $row['file_path'];
      $attachments[] = $row;
    }
    $order['attachments'] = $attachments;
    echo json_encode(['success' => true, 'order' => $order]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Lab order not found']);
  }
}

// Create new lab order
function createLabOrder() {
  global $CON;
  $CON->begin_transaction();
  try {
    $patientId = intval($_POST['patientId']);
    $doctorId = intval($_POST['doctorId']);
    $requestDate = $_POST['requestDate'];
    $dueDate = $_POST['dueDate'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $workType = $_POST['workType'];
    $material = $_POST['material'];
    $shade = $_POST['shade'];
    $teeth = isset($_POST['teeth']) ? implode(',', $_POST['teeth']) : '';
    $instructions = $_POST['instructions'];
    $userId = $_SESSION['uid'];
    $sql = "INSERT INTO lab_orders 
            (patient_id, doctor_id, request_date, due_date, priority, status, work_type, material, shade, teeth, instructions, created_by, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $CON->prepare($sql);
    $stmt->bind_param('iisssssssssi',
      $patientId, $doctorId, $requestDate, $dueDate, $priority, $status,
      $workType, $material, $shade, $teeth, $instructions, $userId
    );
    if ($stmt->execute()) {
      $orderId = $CON->insert_id;
      // Timeline
      $tl = $CON->prepare("INSERT INTO lab_order_timeline (order_id, status, note, user_id, created_at) VALUES (?, ?, ?, ?, NOW())");
      $tlNote = "Order created";
      $tl->bind_param('issi', $orderId, $status, $tlNote, $userId);
      $tl->execute();
      // Attachments
      handleAttachments($orderId, $userId);
      $CON->commit();
      echo json_encode(['success' => true, 'message' => 'Lab order created successfully', 'order_id' => $orderId]);
    } else {
      throw new Exception($stmt->error);
    }
  } catch (Exception $e) {
    $CON->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  }
}

// Update lab order
function updateLabOrder() {
  global $CON;
  $CON->begin_transaction();
  try {
    $orderId = intval($_POST['orderId']);
    $patientId = intval($_POST['patientId']);
    $doctorId = intval($_POST['doctorId']);
    $requestDate = $_POST['requestDate'];
    $dueDate = $_POST['dueDate'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $workType = $_POST['workType'];
    $material = $_POST['material'];
    $shade = $_POST['shade'];
    $teeth = isset($_POST['teeth']) ? implode(',', $_POST['teeth']) : '';
    $instructions = $_POST['instructions'];
    $userId = $_SESSION['uid'];
    // Detect status change
    $curr = $CON->prepare("SELECT status FROM lab_orders WHERE id = ?");
    $curr->bind_param('i', $orderId);
    $curr->execute();
    $currres = $curr->get_result();
    $currOrder = $currres->fetch_assoc();
    $statusChanged = ($currOrder['status'] != $status);
    // Update
    $sql = "UPDATE lab_orders SET 
      patient_id=?, doctor_id=?, request_date=?, due_date=?, priority=?, status=?,
      work_type=?, material=?, shade=?, teeth=?, instructions=?, updated_by=?, updated_at=NOW()
      WHERE id=?";
    $stmt = $CON->prepare($sql);
    $stmt->bind_param('iisssssssssii',
      $patientId, $doctorId, $requestDate, $dueDate, $priority, $status,
      $workType, $material, $shade, $teeth, $instructions, $userId, $orderId
    );
    if ($stmt->execute()) {
      if ($statusChanged) {
        $tl = $CON->prepare("INSERT INTO lab_order_timeline (order_id, status, note, user_id, created_at) VALUES (?, ?, ?, ?, NOW())");
        $tlNote = "Status updated to " . ucfirst($status);
        $tl->bind_param('issi', $orderId, $status, $tlNote, $userId);
        $tl->execute();
      }
      handleAttachments($orderId, $userId);
      $CON->commit();
      echo json_encode(['success' => true, 'message' => 'Lab order updated successfully']);
    } else {
      throw new Exception($stmt->error);
    }
  } catch (Exception $e) {
    $CON->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  }
}

// Delete lab order and related data
function deleteLabOrder() {
  global $CON;
  $orderId = intval($_POST['id']);
  if ($orderId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
    return;
  }
  $CON->begin_transaction();
  try {
    // Delete files
    $att = $CON->prepare("SELECT file_path FROM lab_order_attachments WHERE order_id = ?");
    $att->bind_param('i', $orderId);
    $att->execute();
    $attres = $att->get_result();
    while ($row = $attres->fetch_assoc()) {
      $filePath = '../' . $row['file_path'];
      if (file_exists($filePath)) @unlink($filePath);
    }
    // Delete DB records
    $CON->query("DELETE FROM lab_order_attachments WHERE order_id = $orderId");
    $CON->query("DELETE FROM lab_order_timeline WHERE order_id = $orderId");
    $CON->query("DELETE FROM lab_orders WHERE id = $orderId");
    // Remove folder if empty
    $dir = '../uploads/lab_orders/' . $orderId;
    if (is_dir($dir)) @rmdir($dir);
    $CON->commit();
    echo json_encode(['success' => true, 'message' => 'Lab order deleted successfully']);
  } catch (Exception $e) {
    $CON->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  }
}

// Update order status and add to timeline
function updateOrderStatus() {
  global $CON;
  $orderId = intval($_POST['orderId']);
  $newStatus = $_POST['newStatus'];
  $statusNote = $_POST['statusNote'];
  $userId = $_SESSION['uid'];
  if ($orderId <= 0 || empty($newStatus)) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    return;
  }
  $CON->begin_transaction();
  try {
    $orderSql = "UPDATE lab_orders SET status=?, updated_by=?, updated_at=NOW() WHERE id=?";
    $orderStmt = $CON->prepare($orderSql);
    $orderStmt->bind_param('sii', $newStatus, $userId, $orderId);
    if ($orderStmt->execute()) {
      $timelineSql = "INSERT INTO lab_order_timeline (order_id, status, note, user_id, created_at) VALUES (?, ?, ?, ?, NOW())";
      $timelineStmt = $CON->prepare($timelineSql);
      $timelineStmt->bind_param('issi', $orderId, $newStatus, $statusNote, $userId);
      $timelineStmt->execute();
      $CON->commit();
      echo json_encode(['success' => true, 'message' => 'Order status updated successfully']);
    } else {
      throw new Exception($orderStmt->error);
    }
  } catch (Exception $e) {
    $CON->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  }
}

// Helper: handle file attachments
function handleAttachments($orderId, $userId) {
  global $CON;
  if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
    $uploadDir = '../uploads/lab_orders/' . $orderId . '/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $fileCount = count($_FILES['attachments']['name']);
    for ($i = 0; $i < $fileCount; $i++) {
      if ($_FILES['attachments']['error'][$i] == 0) {
        $fileName = basename($_FILES['attachments']['name'][$i]);
        $targetFile = $uploadDir . $fileName;
        $fileType = $_FILES['attachments']['type'][$i];
        $fileSize = $_FILES['attachments']['size'][$i];
        if (move_uploaded_file($_FILES['attachments']['tmp_name'][$i], $targetFile)) {
          $fileSql = "INSERT INTO lab_order_attachments (order_id, file_name, file_path, file_type, file_size, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
          $fileStmt = $CON->prepare($fileSql);
          $relativePath = 'uploads/lab_orders/' . $orderId . '/' . $fileName;
          $fileStmt->bind_param('isssis', $orderId, $fileName, $relativePath, $fileType, $fileSize, $userId);
          $fileStmt->execute();
        }
      }
    }
  }
}
?>
