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
                document.getElementById('LineName').value = profile.displayName;
                document.getElementById('LineID').value = profile.userId;

            } else {
                liff.login();
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            initializeLiff();
        });

	function closed() {
		liff.closeWindow();
	}

    </script>
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-center mb-4">บันทึกข้อมูลมิเตอร์น้ำ</h2>
                <form method="POST" action="monthly.php">
                    <div class="mb-3">
                        <label for="LineName" class="form-label">LineDisplay เจ้าหน้าที่: </label>
                        <input
                            type="text"
                            id="LineName"
                            name="LineName"
                            class="form-control"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <!-- <label for="LineID" class="form-label">Line ID :</label> -->
                        <input
                            type="hidden"
                            id="LineID"
                            name="LineID"
                            class="form-control"
                            >
                    </div>
                    <div class="mb-3">
                        <label for="MeterID" class="form-label">หมายเลขมิเตอร์:</label>
                        <input
                            type="text"
                            id="MeterID"
                            name="MeterID"
                            class="form-control"
                            autofocus
                            required
                            maxlength="50"
                            pattern="[A-Za-z0-9]+"
                            title="กรุณากรอกหมายเลขมิเตอร์ให้ถูกต้อง">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">บันทึก</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>