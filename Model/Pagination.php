<?php
class Pagination
{
    private $conn;
    public $ID;
    public $ID1;
    public $ID2;
    public $PM;
    public $Page;
    public $Size;
    public $message;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getPagination()
    {
        $strSQL = "SELECT ID, PM FROM value_tb WHERE ID BETWEEN :ID1 AND :ID2";

        // กำหนด Statement ที่จะทำงานกับคำสั่ง SQL
        $stmt = $this->conn->prepare($strSQL);

        // ตรวจสอบข้อมูล
        $this->Page = htmlspecialchars(strip_tags($this->Page));
        $this->Size = htmlspecialchars(strip_tags($this->Size));
        $Page = $this->Page;
        $Size = $this->Size;

        $ID1 = (($Page-1) * $Size) + 1;
        $ID2 = $Page * $Size;

        // กำหนดข้อมูลให้ Parameter
        $stmt->bindParam(":ID1", $ID1);
        $stmt->bindParam(":ID2", $ID2);

        // สั่งให้ SQL ทำงาน
        $stmt->execute();
        
        // ส่งผลลัพธ์ที่ได้จากคำสั่ง SQL ไปใช้งาน
        return $stmt;
    }
}