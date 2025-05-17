<?php
include_once '../../../inc/config.php';

$qid = isset($_POST['qid']) ? intval($_POST['qid']) : -1;

switch ($qid) {
    case 0: // DELETE
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM sidebar_menus WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        echo json_encode(['status' => $success ? 'success' : 'error']);
        break;

    case 1: fnTable(); break;

    case 2: // FETCH SINGLE
        fnGetById();
        break;

    case 3: fnInsert(); break;
    case 4: fnFetchParentMenus(); break;
    case 5: fnPreviewMenu(); break;
    case 6: fnUpdate(); break;
    default:
        echo json_encode(['status' => 'invalid qid']);
        break;
}

function fnTable() {
    global $CON;
    $SQL = "SELECT * FROM `sidebar_menus`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
       0 => 'id',
        1 => 'title',
        2 => 'parent_id',
        3 => 'icon',
        4 => 'url',
        5 => 'label_group',
        6 => 'permission_key',
        7 => 'is_active'
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " WHERE (`title` LIKE '%". $search_value ."%'";
      $SQL .= " OR `id` LIKE '%".$search_value."%')";
    }
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `id` ASC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $status = ($ROW['is_active'] == 1) 
            ? '<span class="badge bg-primary">ACTIVE</span>' 
            : '<span class="badge bg-secondary">INACTIVE</span>';
      $btnShow = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-outline-light showBtn"><span class="text-dark tf-icons bx bx-cog"></span></a>';
      $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-outline-light editBtn"><span class="text-dark tf-icons bx bx-edit"></span></a>';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-outline-light deleteBtn"><span class="text-dark tf-icons bx bx-trash"></a>';
      
      $sub_array = array();
      $sub_array[] = $ROW['id'];
      $sub_array[] = $ROW['title'];
      $sub_array[] = $ROW['parent_id'] ?? '<span class="text-muted">Root</span>';
      $sub_array[] = '<i class="' . $ROW['icon'] . '"></i>';
      $sub_array[] = $ROW['url'] ?? '<span class="text-muted">N/A</span>';
      $sub_array[] = $ROW['label_group'] ?? '-';
      $sub_array[] = $ROW['permission_key'];
      $sub_array[] = $status;
      $sub_array[] = $btnEdit . ' ' . $btnDelete;
      $data[] = $sub_array;
    }

    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows,
      'recordsFiltered' => $total_all_rows,
      'data'=> $data,
    );
    echo  json_encode($output);
  }

  function fnInsert() {
    global $CON;
    $parent_id = ($_POST['parent_id'] == '' || $_POST['parent_id'] == 0) ? '' : $_POST['parent_id'];
    $label_group = $_POST['label_group'];
    $title = $_POST['title'];
    $icon = $_POST['icon'];
    $url = $_POST['url'];
    $permission_key = $_POST['permission_key'];
    $is_dropdown = $_POST['is_dropdown'];
    $is_active = $_POST['is_active'];
    $sort_order = $_POST['sort_order'];
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $SQL = "UPDATE `sidebar_menus` SET `parent_id`='$parent_id', `label_group`='$label_group', `title`='$title', `icon`='$icon', `url`='$url', `permission_key`='$permission_key', `is_dropdown`='$is_dropdown', `is_active`='$is_active', `sort_order`='$sort_order' WHERE id = '$id'";
    } else {
        $SQL = "INSERT INTO `sidebar_menus` (`parent_id`, `label_group`, `title`, `icon`, `url`, `permission_key`, `is_dropdown`, `is_active`, `sort_order`) VALUES ('$parent_id', '$label_group', '$title', '$icon', '$url', '$permission_key', '$is_dropdown', '$is_active', '$sort_order')";
    }

    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'success') : array('status' => 'error');
    echo json_encode($data);
}

function fnFetchParentMenus() {
    global $CON;
    $SQL = "SELECT * FROM `sidebar_menus` WHERE `parent_id` IS NULL";
    $QUERY = mysqli_query($CON, $SQL);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
        $data[] = array(
            'id' => $ROW['id'],
            'title' => $ROW['title']
        );
    }
    echo json_encode($data);
}

function fnPreviewMenu() {
    global $CON;

    // Get top-level menu items with label groups
    $result = mysqli_query($CON, "
        SELECT * FROM sidebar_menus 
        WHERE (parent_id IS NULL OR parent_id = 0) AND is_active = 1 
        ORDER BY id ASC, label_group ASC, sort_order ASC
    ");

    $grouped = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $group = $row['label_group'] ?: '';

        if (!isset($grouped[$group])) {
            $grouped[$group] = [];
        }

        if ($row['is_dropdown'] == 1) {
            $children = [];
            $child_result = mysqli_query($CON, "SELECT * FROM sidebar_menus WHERE parent_id = {$row['id']} AND is_active = 1 ORDER BY sort_order ASC");
            while ($child = mysqli_fetch_assoc($child_result)) {
                $children[] = $child;
            }
            $row['children'] = $children;
        }

        $grouped[$group][] = $row;
    }

    echo json_encode($grouped);
}

function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $parent_id = ($_POST['parent_id'] == '' || $_POST['parent_id'] == 0) ? 'NULL' : $_POST['parent_id'];
    $label_group = $_POST['label_group'];
    $title = $_POST['title'];
    $icon = $_POST['icon'];
    $url = $_POST['url'];
    $permission_key = $_POST['permission_key'];
    $is_dropdown = $_POST['is_dropdown'];
    $is_active = $_POST['is_active'];
    $sort_order = $_POST['sort_order'];

    $SQL = "UPDATE `sidebar_menus` SET `parent_id`='$parent_id', `label_group`='$label_group', `title`='$title', `icon`='$icon', `url`='$url', `permission_key`='$permission_key', `is_dropdown`='$is_dropdown', `is_active`='$is_active', `sort_order`='$sort_order' WHERE id = '$id'";
    
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'success') : array('status' => 'error');
    echo json_encode($data);
}

function fnGetById() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "SELECT * FROM `sidebar_menus` WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
}