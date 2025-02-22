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
    $UserID = $_POST["UserID"];
    $Name = $_POST["Name"];
    $Address = $_POST["Address"];
    $MeterID = $_POST["MeterID"];
    $InstallationDate = $_POST["InstallationDate"];
    $PhoneNumber = $_POST["PhoneNumber"];
    $LineID = $_POST["LineID"];
    $LineDisplay = $_POST["LineDisplay"];

    // $checkID = "SELECT * FROM water_users WHERE UserID = ?";
    // $stmtCheck = $conn->prepare($checkID);
    // $stmtCheck->bind_param("i", $UserID);
    // $stmtCheck->execute();
    // $resultCheck = $stmtCheck->get_result();

    // if ($resultCheck->num_rows > 0) {
    //     echo "<script>alert('UserID ซ้ำ! กรุณากรอกหมายเลขใหม่');</script>";
    // } else {
        $sql = "INSERT INTO water_users (UserID, Name, Address, MeterID, InstallationDate, PhoneNumber,LineID,LineDisplay) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssss", $UserID, $Name, $Address, $MeterID, $InstallationDate, $PhoneNumber, $LineID, $LineDisplay);

        if ($stmt->execute()) {
            echo "<script>alert('บันทึกข้อมูลสำเร็จ!');</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาด: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    // }
    $stmtCheck->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

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
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <div class="container mt-3">
                    <h2>ทะเบียนข้อมูลผู้ใช้น้ำ</h2>
                    <form method="POST" >
                        <div class="form-group">
<!--                            <label for="UserID">หมายเลขผู้ใช้น้ำ:</label> -->
                            <input type="hidden" id="UserID" name="UserID" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="Name">ชื่อผู้ใช้น้ำ:</label>
                            <input type="text" id="Name" name="Name" class="form-control"  required>
                        </div>
                        <div class="form-group">
                            <label for="Address">ที่อยู่:</label>
                            <textarea id="Address" name="Address" rows="3" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="MeterID">หมายเลขมิเตอร์:</label>
                            <input type="text" id="MeterID" name="MeterID" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="InstallationDate">วันที่ติดตั้ง:</label>
                            <input type="date" id="InstallationDate" name="InstallationDate" class="form-control" required>
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
                                title="กรุณากรอกเบอร์โทรศัพท์ 10 หลัก">
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
            </div>
        </div>
    </div>
</body>

</html>