<?php
// public/api/v1/locations/index.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include_once '../../../inc/config.php';

$level  = $_GET['level']    ?? '';
$parent = $_GET['parent']   ?? '';  // this will be a “code”, not an id

switch ($level) {
  case 'province':
    // get distinct provinces
    $sql = "
      SELECT DISTINCT province_code AS code,
                      province_name_en AS name_en,
                      province_name_kh AS name_kh
        FROM address
       ORDER BY province_name_en
    ";
    break;

  case 'district':
    if (!$parent) {
      http_response_code(400);
      exit(json_encode(['success'=>false,'message'=>'province code required']));
    }
    $p = $CON->real_escape_string($parent);
    $sql = "
      SELECT DISTINCT district_code AS code,
                      district_name_en AS name_en,
                      district_name_kh AS name_kh
        FROM address
       WHERE province_code = '$p'
       ORDER BY district_name_en
    ";
    break;

  case 'commune':
    if (!$parent) {
      http_response_code(400);
      exit(json_encode(['success'=>false,'message'=>'district code required']));
    }
    $p = $CON->real_escape_string($parent);
    $sql = "
      SELECT DISTINCT commune_code AS code,
                      commune_name_en AS name_en,
                      commune_name_kh AS name_kh
        FROM address
       WHERE district_code = '$p'
       ORDER BY commune_name_en
    ";
    break;

  case 'village':
    if (!$parent) {
      http_response_code(400);
      exit(json_encode(['success'=>false,'message'=>'commune code required']));
    }
    $p = $CON->real_escape_string($parent);
    $sql = "
      SELECT DISTINCT village_code AS code,
                      village_name_en AS name_en,
                      village_name_kh AS name_kh
        FROM address
       WHERE commune_code = '$p'
       ORDER BY village_name_en
    ";
    break;

  default:
    http_response_code(400);
    exit(json_encode(['success'=>false,'message'=>'Invalid level']));
}

$result = $CON->query($sql);
if (!$result) {
  http_response_code(500);
  exit(json_encode(['success'=>false,'message'=>$CON->error]));
}

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode(['success'=>true,'data'=>$data]);
