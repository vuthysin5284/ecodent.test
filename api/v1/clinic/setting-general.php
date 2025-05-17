<?php
// public/api/v1/patients/index.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {    
    exit;
}

include_once '../../../inc/config.php';

// helper to send JSON + exit
function send($code, $payload) {
    http_response_code($code);
    echo json_encode($payload);
    exit;
}

// read raw body for PUT/DELETE
$input = json_decode(file_get_contents('php://input'), true);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            // READ one
            $id = (int)$_GET['id'];
            if (!$id) send(400, ['success'=>false,'message'=>'Invalid ID']);
            $id = $CON->real_escape_string($id);
            $res = $CON->query("SELECT * FROM clinic WHERE id = $id");
            if (!$res || $res->num_rows === 0) {
                send(404, ['success'=>false,'message'=>'Not found']);
            }
            send(200, ['success'=>true,'data'=>$res->fetch_assoc()]);
        } else {
            // LIST all
            // $res = $CON->query(
            //   "SELECT id, first_name, last_name, dob, phone 
            //      FROM patients 
            //     ORDER BY last_name"
            // );
            // $data = [];
            // while ($row = $res->fetch_assoc()) {
            //     $data[] = $row;
            // }
            // send(200, ['success'=>true,'data'=>$data]);
        }
        break;

    case 'POST':
        // CREATE
        // if (empty($input['first_name']) || empty($input['last_name']) || empty($input['dob'])) {
        //     send(422, ['success'=>false,'message'=>'first_name, last_name & dob required']);
        // }
        // escape
        // $fn = $CON->real_escape_string($input['first_name']);
        // $ln = $CON->real_escape_string($input['last_name']);
        // $dob= $CON->real_escape_string($input['dob']);
        // $ph = $CON->real_escape_string($input['phone']   ?? '');
        // $em = $CON->real_escape_string($input['email']   ?? '');
        // $ad = $CON->real_escape_string($input['address'] ?? '');

        // $sql = "
        //   INSERT INTO patients 
        //     (first_name,last_name,dob,phone,email,address)
        //   VALUES
        //     ('$fn','$ln','$dob','$ph','$em','$ad')
        // ";
        // if (!$CON->query($sql)) {
        //     send(500, ['success'=>false,'message'=>$CON->error]);
        // }
        // send(201, ['success'=>true,'data'=>['id'=>$CON->insert_id]]);
        break;

    case 'PUT':
        // UPDATE
        $id = isset($input['id']) ? (int)$input['id'] : 1;
        if (!$id) send(400, ['success'=>false,'message'=>'Missing ID']);
        // escape all
        $name_en = $CON->real_escape_string($input['name_en'] ?? '');
        $name_kh = $CON->real_escape_string($input['name_kh']  ?? '');
        $slug = $CON->real_escape_string($input['slug']        ?? '');
        $phone = $CON->real_escape_string($input['phone']      ?? '');
        $email = $CON->real_escape_string($input['email']    ?? '');
        $province = $CON->real_escape_string($input['province']    ?? '');
        $district = $CON->real_escape_string($input['district']    ?? '');
        $commune = $CON->real_escape_string($input['commune']    ?? '');
        $village = $CON->real_escape_string($input['village']    ?? '');
        $address = $CON->real_escape_string($input['address']    ?? '');
        $id  = $CON->real_escape_string($id);

        $sql = "
            UPDATE clinic 
            SET 
                name_en = '$name_en',
                name_kh = '$name_kh',
                slug = '$slug',
                phone = '$phone',
                email = '$email',
                province_code = '$province',
                district_code = '$district',
                commune_code = '$commune',
                village_code = '$village',
                address = '$address'
            WHERE id = $id
        ";
        if (!$CON->query($sql)) {
            send(500, ['success'=>false,'message'=>$CON->error]);
        }
        send(200, ['success'=>true]);
        break;

    case 'DELETE':
        // // DELETE
        // $id = isset($input['id']) ? (int)$input['id'] : 0;
        // if (!$id) send(400, ['success'=>false,'message'=>'Missing ID']);
        // $id = $CON->real_escape_string($id);
        // if (!$CON->query("DELETE FROM patients WHERE id = $id")) {
        //     send(500, ['success'=>false,'message'=>$CON->error]);
        // }
        // send(200, ['success'=>true]);
        break;

    default:
        send(405, ['success'=>false,'message'=>'Method not allowed']);
}
