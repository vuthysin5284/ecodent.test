<?php
session_start();
$log = $_SESSION['uid'];
if (!isset($log)) {
  header('HTTP/1.1 401 Unauthorized');
  exit;
}

include_once '../../../inc/config.php';
header('Content-Type: application/json');

// Get action type
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

switch ($action) {
  case 'get':
    getFeedback();
    break;
  case 'read':
    markAsRead();
    break;
  case 'delete':
    deleteFeedback();
    break;
  default:
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    break;
}

function getFeedback() {
  global $CON;
  $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
  
  if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid feedback ID']);
    return;
  }
  
  $sql = "SELECT 
            f.*, 
            u.username, 
            u.role as user_role
          FROM 
            feedback f
          LEFT JOIN 
            users u ON f.user_id = u.id
          WHERE 
            f.id = ?";
            
  $stmt = $CON->prepare($sql);
  $stmt->bind_param("i", $id);
  
  if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $feedback = $result->fetch_assoc();
      
      // Format the date for display
      $feedback['created_at'] = date('M d, Y h:i A', strtotime($feedback['created_at']));
      
      echo json_encode(['success' => true, 'feedback' => $feedback]);
    } else {
      echo json_encode(['success' => false, 'message' => 'Feedback not found']);
    }
  } else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
  }
}

function markAsRead() {
  global $CON;
  $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
  
  if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid feedback ID']);
    return;
  }
  
  $sql = "UPDATE feedback SET is_read = 1, read_at = NOW() WHERE id = ?";
  $stmt = $CON->prepare($sql);
  $stmt->bind_param("i", $id);
  
  if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Feedback marked as read']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
  }
}

function deleteFeedback() {
  global $CON;
  $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
  
  if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid feedback ID']);
    return;
  }
  
  $sql = "DELETE FROM feedback WHERE id = ?";
  $stmt = $CON->prepare($sql);
  $stmt->bind_param("i", $id);
  
  if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Feedback deleted successfully']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
  }
}
?>
