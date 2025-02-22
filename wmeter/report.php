<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Billing Report</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Water Billing Report</h1>

        <!-- Search Form -->
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="OrderID" class="form-control" placeholder="Search by Order ID" value="<?php echo isset($_GET['OrderID']) ? $_GET['OrderID'] : ''; ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" name="MeterID" class="form-control" placeholder="Search by Meter ID" value="<?php echo isset($_GET['MeterID']) ? $_GET['MeterID'] : ''; ?>">
                </div>
                <div class="col-md-3">
                    <input type="date" name="BillingDate" class="form-control" value="<?php echo isset($_GET['BillingDate']) ? $_GET['BillingDate'] : ''; ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>

        <!-- Results Table -->
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>รายการ</th>
                    <th>เลขมิเตอร์</th>
                    <th>Billing Date</th>
                    <th>จดครั้งก่อน</th>
                    <th>จดครั้งนี้</th>
                    <th>ยูนิตละ</th>
                    <th>ค่าเช่ามิเตอร์</th>
                    <th>รวมเป็นเงิน</th>
                    <th>พนักงาน</th>
                </tr>
                </thead>
            <tbody>
                <?php
                // Database connection
		$host = "localhost";
		$user = "root";
		$pass = "";
		$db = "Project";
                
		$conn = new mysqli($host, $user, $pass, $db);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare search conditions
                $conditions = [];
                if (!empty($_GET['OrderID'])) {
                    $conditions[] = "OrderID = '" . $conn->real_escape_string($_GET['OrderID']) . "'";
                }
                if (!empty($_GET['MeterID'])) {
                    $conditions[] = "MeterID = '" . $conn->real_escape_string($_GET['MeterID']) . "'";
                }
                if (!empty($_GET['BillingDate'])) {
                    $conditions[] = "BillingDate = '" . $conn->real_escape_string($_GET['BillingDate']) . "'";
                }

                // Build the SQL query
                $sql = "SELECT OrderID, MeterID, BillingDate, PreviousRecorded, LastRecorded, 
                               WaterUnitCost, MeterRentalFee, Total, StaffID 
                        FROM payment_notification_table ";
                if (!empty($conditions)) {
                    $sql .= " WHERE " . implode(" AND ", $conditions);
                }
		
		$sql .= " Order by  OrderID DESC ";

		//echo $sql . "<br/>";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['OrderID']}</td>
                                <td>{$row['MeterID']}</td>
                                <td>{$row['BillingDate']}</td>
                                <td>{$row['PreviousRecorded']}</td>
                                <td>{$row['LastRecorded']}</td>
                                <td>{$row['WaterUnitCost']}</td>
                                <td>{$row['MeterRentalFee']}</td>
                                <td>{$row['Total']}</td>
                                <td>{$row['StaffID']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='text-center'>No data available</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
