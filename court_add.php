<?php
require_once "header.php";
require_once "config.php";

// เฉพาะ admin level 3 เท่านั้น
if (!isset($_SESSION['username']) || $_SESSION['level'] != 3) {
    header("Location: index.php");
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
    <form action="court_add_submit.php" method="post">
        <h2>เพิ่มสนามใหม่</h2>

        <div class="login-group">
            <label>หมายเลขสนาม</label>
            <input type="number" name="court_number" min="1" required>
        </div>

        <div class="login-group">
            <label>ชื่อสนาม</label>
            <input type="text" name="court_name" required>
        </div>

        <div class="login-group">
            <label>ราคาต่อชั่วโมง (บาท)</label>
            <input type="number" name="price_per_hour" min="1" step="0.01" required>
        </div>

        <div class="login-group">
            <label>สถานะ</label>
            <select name="is_active">
                <option value="1">เปิดใช้งาน</option>
                <option value="0">ปิดปรับปรุง</option>
            </select>
        </div>

        <div class="login-buttons">
            <input type="submit" value="บันทึก" class="btn-submit">
            <a href="court_management.php"><input type="button" value="ยกเลิก" class="btn-back"></a>
        </div>
    </form>
</div>
</html>

<?php require_once "footer.php"; ?>
