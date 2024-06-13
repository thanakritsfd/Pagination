<?php
header("Access-control-allow-origin: *");
header("content-type: application/json; charset=UTF-8");

require_once "./../connect.php";
require_once "./../Model/Pagination.php";

$databaseConnect = new DatabaseConnect();
$connDB = $databaseConnect->getConnection();

$Pagination = new Pagination($connDB);

//สร้างตัวแปรเก็บค่าของข้อมูลที่ส่งมาซึ่งเป็น JSON ที่ทำการ decode แล้ว
$data = json_decode(file_get_contents("php://input"));

//เอาข้อมูลที่ถูก Decode ไปเก็บในตัวแปร
$Pagination->Page = $data->Page;
$Pagination->Size = $data->Size;

//เรียกใช้ Function ตามวัตถุประสงค์ของ API ตัวนี้
$stmt = $Pagination->getPagination();

//นับแถวเพื่อดูว่าได้ข้อมูลมาไหม 
$numrow = $stmt->rowCount();

//สร้างตัวแปรมาเก็บข้อมูลที่ได้จากการเรียกใช้ function เพื่อส่งกับไปยังส่วนที่เรียกใช้ API
$Pagination_arr = array();

//ตรวจสอบผล และส่งกลับไปยังส่วนที่เรียกใช้ API
if ($numrow > 0) {
    //มีข้อมูล เอาข้อมูลใสาตัวแปร และเตรียมส่งกลับ
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $Pagination_item = array(
            "ID" => $ID,
            "PM" => $PM
        );

        array_push($Pagination_arr, $Pagination_item);
    }
} else {
    //ไม่มีข้อมูล เอาข้อมูลใสาตัวแปร และเตรียมส่งกลับ
    $Pagination_item = array(
        "massage" => "0"
    );
        array_push($Pagination_arr, $Pagination_item);
}

//คำสั่งจัดการข้อมูลใหเฃ้เป็น JSON เพื่อส่งกลับ
http_response_code(200);
echo json_encode($Pagination_arr, JSON_UNESCAPED_UNICODE);