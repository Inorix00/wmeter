<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "Project";


$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $StaffID = $_POST["StaffID"];
    $NameStaff = $_POST["NameStaff"];
    $PhoneNumber = $_POST["PhoneNumber"];
    $LineDisplay= $_POST["LineDisplay"];
    $LineID= $_POST["LineID"];

	echo " xxxxx 1 <br/>";
    $sql = "INSERT INTO staff (NameStaff, PhoneNumber, LineID, LineDisplay) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss",  $NameStaff, $PhoneNumber, $LineID, $LineDisplay);

    if ($stmt->execute()) {
	echo " xxxxx 2 <br/>";

        echo "<script>alert('บันทึกข้อมูลพนักงานสำเร็จ!'); location.replace('index.php'); </script>";
    } else {
	echo " xxxxx 3 " . $stmt->error . "<br/>";
        echo "<script>alert('เกิดข้อผิดพลาด: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกข้อมูลพนักงาน</title>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกข้อมูลมิเตอร์น้ำ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
    <script>
        async function initializeLiff() {
            await liff.init({
                liffId: "2006736098-42xR2MZm"
            });

            if (liff.isLoggedIn()) {
                const profile = await liff.getProfile();
                document.getElementById('LineDisplay').value = profile.displayName;
                document.getElementById('LineID').value = profile.userId;
            } else {
                liff.login();
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            initializeLiff();
        });
    </script>
</head>
<body>
<div class="container mt-3">
        <h2>บันทึกข้อมูลพนักงาน</h2>
        <form method="POST">
            <div class="form-group">
                <!-- <label for="StaffID">หมายเลขพนักงาน:</label> -->
                <input type="hidden" id="StaffID" name="StaffID" required>
            </div>
            <div class="form-group">
                <label for="NameStaff">ชื่อพนักงาน:</label>
                <input type="text" id="NameStaff" name="NameStaff" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="PhoneNumber">เบอร์โทรศัพท์:</label>
                <input 
                    type="tel" 
                    id="PhoneNumber" 
                    name="PhoneNumber" 
                    required 
                    pattern="\d{10}" 
                    maxlength="10" 
class="form-control" 
                    title="กรุณากรอกเบอร์โทรศัพท์ 10 หลัก"
                >
            </div>
                        <div class="form-group">
                            <label for="LineDisplay">Line display:</label>
                            <input
                                type="text"
                                id="LineDisplay"
                                name="LineDisplay"
class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <!-- <label for="LineID">Line ID:</label> -->
                            <input
                                type="hidden"
                                id="LineID"
                                name="LineID"
                                required>
                        </div>

            <button type="submit" class="btn btn-success">บันทึก</button>
        </form>
    </div>
</body>
</html>