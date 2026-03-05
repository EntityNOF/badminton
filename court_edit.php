<?php
require_once "header.php";
require_once "config.php";

// เฉพาะ admin level 3 เท่านั้น
if (!isset($_SESSION['username']) || $_SESSION['level'] != 3) {
    header("Location: index.php");
    exit;
}

// รับ court_id จาก URL
$court_id = isset($_GET['court_id']) ? intval($_GET['court_id']) : 0;

if ($court_id <= 0) {
    header("Location: court_management.php");
    exit;
}

// ดึงข้อมูลสนามที่จะแก้ไข
$row = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM court WHERE court_id = $court_id"));

if (!$row) {
    $_SESSION['errors_msg'] = "ไม่พบสนามที่ต้องการแก้ไข";
    header("Location: court_management.php");
    exit;
}

// รับค่าจาก form เมื่อกด submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $court_number   = intval($_POST['court_number']);
    $court_name     = $_POST['court_name'];
    $price_per_hour = $_POST['price_per_hour'];
    $is_active      = intval($_POST['is_active']);

    // ตรวจสอบข้อมูลครบถ้วน
    if (empty($court_name) || $court_number <= 0 || $price_per_hour <= 0) {
        $_SESSION['errors_msg'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header("Location: court_edit.php?court_id=" . $court_id);
        exit;
    }

    // ตรวจสอบหมายเลขสนามซ้ำ (ยกเว้นตัวเอง)
    $check = mysqli_query($connect, "SELECT court_id FROM court WHERE court_number = $court_number AND court_id <> $court_id");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['errors_msg'] = "หมายเลขสนามนี้มีอยู่ในระบบแล้ว";
        header("Location: court_edit.php?court_id=" . $court_id);
        exit;
    }

    // อัปเดตข้อมูล
    $sql = "UPDATE court SET
                court_number   = $court_number,
                court_name     = '$court_name',
                price_per_hour = $price_per_hour,
                is_active      = $is_active
            WHERE court_id = $court_id";

    if (mysqli_query($connect, $sql)) {
        $_SESSION['success_msg'] = "แก้ไขข้อมูลสนามเรียบร้อยแล้ว";
        header("Location: court_management.php");
    } else {
        $_SESSION['errors_msg'] = "เกิดข้อผิดพลาด: " . mysqli_error($connect);
        header("Location: court_edit.php?court_id=" . $court_id);
    }
    exit;
}

// แสดง flash message
if (isset($_SESSION['errors_msg'])) {
    echo "<p class='msg-error'>" . $_SESSION['errors_msg'] . "</p>";
    unset($_SESSION['errors_msg']);
}
?>

<html>
<link rel="stylesheet" href="style.css">
<div class="login">
    <form action="court_edit.php?court_id=<?php echo $court_id; ?>" method="post">
        <h2>แก้ไขข้อมูลสนาม</h2>

        <div class="login-group">
            <label>หมายเลขสนาม</label>
            <input type="number" name="court_number" min="1"
                   value="<?php echo $row['court_number']; ?>" required>
        </div>

        <div class="login-group">
            <label>ชื่อสนาม</label>
            <input type="text" name="court_name"
                   value="<?php echo $row['court_name']; ?>" required>
        </div>

        <div class="login-group">
            <label>ราคาต่อชั่วโมง (บาท)</label>
            <input type="number" name="price_per_hour" min="1" step="0.01"
                   value="<?php echo $row['price_per_hour']; ?>" required>
        </div>

        <div class="login-group">
            <label>สถานะ</label>
            <select name="is_active">
                <option value="1" <?php if ($row['is_active'] == 1) echo 'selected'; ?>>เปิดใช้งาน</option>
                <option value="0" <?php if ($row['is_active'] == 0) echo 'selected'; ?>>ปิดปรับปรุง</option>
            </select>
        </div>

        <div class="login-buttons">
            <input type="submit" value="บันทึก">
            <a href="court_management.php"><input type="button" value="ยกเลิก"></a>
        </div>
    </form>
</div>
</html>

<?php require_once "footer.php"; ?>
