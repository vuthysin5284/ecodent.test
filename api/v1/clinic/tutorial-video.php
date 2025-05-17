<?php
  session_start();
  $log = $_SESSION['uid'];
  if (!isset($log)) { header('Location: login.php'); }
  error_reporting(0);
  include_once '../../../inc/config.php';
  
  // Handle different actions
  $action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
  
  switch ($action) {
    case 'add':
      addVideo();
      break;
    case 'edit':
      editVideo();
      break;
    case 'delete':
      deleteVideo();
      break;
    case 'get':
      getVideo();
      break;
    default:
      header('Location: ../../../page/manage-tutorial.php');
      exit;
  }
  
  // Add new tutorial video
  function addVideo() {
    global $CON;
    
    $title = $_POST['title'];
    $video_link = $_POST['video_link'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $date_added = date('Y-m-d');
    
    // Extract YouTube embed link
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_link, $matches);
    $youtube_id = isset($matches[1]) ? $matches[1] : '';
    $embed_link = 'https://www.youtube.com/embed/' . $youtube_id;
    
    $sql = "INSERT INTO tutorial_videos (title, video_link, embed_link, category, description, date_added) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $CON->prepare($sql);
    $stmt->bind_param("ssssss", $title, $video_link, $embed_link, $category, $description, $date_added);
    
    if ($stmt->execute()) {
      $_SESSION['success'] = "Tutorial video added successfully";
    } else {
      $_SESSION['error'] = "Error adding tutorial video: " . $CON->error;
    }
    
    header('Location: ../../../page/manage-tutorial.php');
    exit;
  }
  
  // Edit existing tutorial video
  function editVideo() {
    global $CON;
    
    $video_id = $_POST['video_id'];
    $title = $_POST['title'];
    $video_link = $_POST['video_link'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    
    // Extract YouTube embed link
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_link, $matches);
    $youtube_id = isset($matches[1]) ? $matches[1] : '';
    $embed_link = 'https://www.youtube.com/embed/' . $youtube_id;
    
    $sql = "UPDATE tutorial_videos 
            SET title = ?, video_link = ?, embed_link = ?, category = ?, description = ? 
            WHERE id = ?";
    
    $stmt = $CON->prepare($sql);
    $stmt->bind_param("sssssi", $title, $video_link, $embed_link, $category, $description, $video_id);
    
    if ($stmt->execute()) {
      $_SESSION['success'] = "Tutorial video updated successfully";
    } else {
      $_SESSION['error'] = "Error updating tutorial video: " . $CON->error;
    }
    
    header('Location: ../../../page/manage-tutorial.php');
    exit;
  }
  
  // Delete tutorial video
  function deleteVideo() {
    global $CON;
    
    $video_id = $_POST['video_id'];
    
    $sql = "DELETE FROM tutorial_videos WHERE id = ?";
    
    $stmt = $CON->prepare($sql);
    $stmt->bind_param("i", $video_id);
    
    if ($stmt->execute()) {
      $_SESSION['success'] = "Tutorial video deleted successfully";
    } else {
      $_SESSION['error'] = "Error deleting tutorial video: " . $CON->error;
    }
    
    header('Location: ../../../page/manage-tutorial.php');
    exit;
  }
  
  // Get video data for editing
  function getVideo() {
    global $CON;
    
    $video_id = $_GET['video_id'];
    
    $sql = "SELECT * FROM tutorial_videos WHERE id = ?";
    
    $stmt = $CON->prepare($sql);
    $stmt->bind_param("i", $video_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
      $video = $result->fetch_assoc();
      echo json_encode(['success' => true, 'video' => $video]);
    } else {
      echo json_encode(['success' => false, 'message' => 'Video not found']);
    }
    
    exit;
  }
?>
