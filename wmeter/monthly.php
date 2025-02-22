<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "Project";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST["LastRecorded"])) {
    $MeterID = $_POST["MeterID"];
    $LineID = $_POST["LineID"];

	//ตรวจ MeterID
    // จาก MeterID --> Name
    $checkID = "SELECT * FROM `water_users` WHERE `MeterID` = ?";
    $stmtCheck = $conn->prepare($checkID);
    $stmtCheck->bind_param("s", $MeterID);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
	$wname="xxx";
    if ($resultCheck->num_rows <= 0) {
        echo "<script>alert(' ไม่พบ MeterID นี้ '); location.replace('index.php');</script>";
    }else{
	$row = $resultCheck->fetch_assoc();
	$wname=$row['Name'];
}


    // จาก MeterID --> LastRecorded 
    $checkID = "SELECT * FROM `monthly_meter_reading_record` WHERE `MeterID` = ? ORDER by `DateTime` DESC LIMIT 1; ";
    $stmtCheck = $conn->prepare($checkID);
    $stmtCheck->bind_param("i", $MeterID);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        $row = $resultCheck->fetch_assoc();
        $PreviousRecorded = $row['LastRecorded'];
    } else {
        echo "<script>alert(' จดรายการใหม่ ');</script>";
	$PreviousRecorded = 0; //1000000;
    }

    // จาก LineID --> StaffID 
	//echo " SELECT * FROM `staff` WHERE `LineID` = " . $LineID;
    $checkID = "SELECT * FROM `staff` WHERE `LineID` = ?";
    $stmtCheck = $conn->prepare($checkID);
    $stmtCheck->bind_param("s", $LineID);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        $row = $resultCheck->fetch_assoc();
        $StaffID= $row['StaffID'];
	//echo " StaffID : " .  $StaffID;
    } else {
        echo "<script>alert(' ไม่มีข้อมูล Staff รายนี้');</script>";
    }
} elseif (!empty($_POST["LastRecorded"])) {

//    if (!preg_match('/^\d{7}$/', $_POST["LastRecorded"])) {
	//echo intval($_POST["LastRecorded"]) . "   " . intval($_POST["PreviousRecorded"]);
        //echo "<script> alert('xxx');</script>";

    if (intval($_POST["LastRecorded"]) < intval($_POST["PreviousRecorded"]) ) {
        echo "<script> alert('จดครั้งนี้ควรมากกว่าครั้งก่อน'); location.replace('index.php');</script>";
    } else {
        $MeterID = $_POST["MeterID"];
        $LastRecorded = $_POST["LastRecorded"];
        $StaffID = $_POST["StaffID"];
        //echo "xxxxxx 1 <br>";
        $sql = "INSERT INTO monthly_meter_reading_record (MeterID, LastRecorded, StaffID, DateTime) VALUES (?, ?, ?, NOW())";
        //echo "xxxxxx 2 <br>";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $MeterID, $LastRecorded, $StaffID);
        //echo "xxxxxx 3 <br>";

        if ($stmt->execute()) {
            //echo "xxxxxx 4 <br>";
            $stmt->close();
            $conn->close();
            echo "<script>alert('บันทึกข้อมูลสำเร็จ!');  location.replace('index.php');</script>";
//            echo "<script>alert('บันทึกข้อมูลสำเร็จ!');   liff.closeWindow();</script>";

 
        } else {
            //echo "xxxxxx 5 <br>";
                        echo "<script>alert('เกิดข้อผิดพลาด: " . $stmt->error . "');</script>";
            //echo ("Statement failed: " . $stmt->error . "<br>");
            //          echo "<script>alert($stmt->error);</script>";
        }
        $stmt->close();
    }
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
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-center mb-4">บันทึกข้อมูลมิเตอร์น้ำ</h2>
                <form method="POST">
                    <div class="mb-2">
                        <label for="LineName" class="form-label">วันที่บันทึก : </label>
                        <input
                            type="text"
                            value="<?php echo date('d-m-Y'); ?>"
                            id="regDate"
                            name="regDate"
                            class="form-control"
                            disabled
                            readonly>
                    </div>
                    <div class="mb-2">
                        <!-- <label for="LineID" class="form-label">Line ID :</label> -->
                        <input
                            type="hidden"
                            id="LineID"
                            name="LineID"
                            class="form-control"
                            readonly>
                    </div>
                    <div class="mb-2">
                        <label for="MeterID" class="form-label">หมายเลขมิเตอร์:</label>
                        <input
                            type="text"
                            id="MeterID"
                            name="MeterID"
                            class="form-control"
                            value="<?php echo $MeterID; ?>"
                            required
                            maxlength="50"
                            pattern="[A-Za-z0-9]+"
                            title="กรุณากรอกหมายเลขมิเตอร์ให้ถูกต้อง"
                            readonly>
                    </div>
                    <div class="mb-2">
                        <label for="wname" class="form-label">ชื่อผู้ใช้น้ำ:</label>
                        <input
                            type="text"
                            id="wname"
                            name="wname"
                            class="form-control"
                            value="<?php echo $wname; ?>"
				disabled
                            readonly>
                    </div>
                    <div class="mb-2">
                        <label for="LastRecorded" class="form-label">จดครั้งก่อน:</label>
                        <input
                            type="number"
                            id="PreviousRecorded"
                            name="PreviousRecorded"
                            value="<?php echo $PreviousRecorded; ?>"
                            class="form-control"
                            readonly>
                    </div>
                    <div class="mb-2">
                        <label for="LastRecorded" class="form-label">จดครั้งนี้:</label>
                        <input
                            type="number"
                            id="LastRecorded"
                            name="LastRecorded"
                            class="form-control"
                            required
                            maxlength="7"
                            autofocus
                            value="<?php echo $PreviousRecorded; ?>"
                            placeholder="จดครั้งนี้">
                    </div>
			<!--
                    <div class="mb-2">
                        <label for="StaffID" class="form-label">รหัสพนักงาน:</label>
                        <input
                            type="number"
                            id="StaffID"
                            name="StaffID"
                            value="<?php echo $StaffID; ?>"
                            class="form-control"
                            required
                            readonly>
			-->
                        <input
                            type="hidden"
                            id="StaffID"
                            name="StaffID"
                            value="<?php echo $StaffID; ?>"
                            class="form-control" >
			<!--
                    </div>
			-->
                    <button type="submit" class="btn btn-primary w-100">บันทึก</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>