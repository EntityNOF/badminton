<?php
require_once "header.php";
require_once "config.php";

// เฉพาะ admin level 3 เท่านั้น
if (!isset($_SESSION['username']) || $_SESSION['level'] != 3) {
    header("Location: index.php");
    exit;
}

// flash message
if (isset($_SESSION['success_msg'])) {
    echo "<p class='msg-success'>" . $_SESSION['success_msg'] . "</p>";
    unset($_SESSION['success_msg']);
}
if (isset($_SESSION['errors_msg'])) {
    echo "<p class='msg-error'>" . $_SESSION['errors_msg'] . "</p>";
    unset($_SESSION['errors_msg']);
}

// ดึงข้อมูลสนามทั้งหมด
$result = mysqli_query($connect, "SELECT * FROM court ORDER BY court_number ASC");
?>

<html>
<link rel="stylesheet" href="style.css">
<div class="admin-container">
    <br>
    <h2>จัดการสนามแบดมินตัน</h2>
    <br>
    <a href="court_add.php"><button class="btn-book">+ เพิ่มสนามใหม่</button></a>
    <br><br>
    <table>
        <tr>
            <th class="um-col-id">#</th>
            <th>หมายเลขสนาม</th>
            <th>ชื่อสนาม</th>
            <th>ราคา/ชั่วโมง (บาท)</th>
            <th>สถานะ</th>
            <th class="um-col-action">Actions</th>
        </tr>
        <?php if ($result) { while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr class="text-center">
            <td class="um-col-id"><?php echo $row['court_id']; ?></td>
            <td><?php echo $row['court_number']; ?></td>
            <td><?php echo $row['court_name']; ?></td>
            <td><?php echo number_format($row['price_per_hour'], 2); ?></td>
            <td>
                <?php if ($row['is_active'] == 1) { ?>
                    <span class="text-green">เปิดใช้งาน</span>
                <?php } else { ?>
                    <span class="text-gray">ปิดปรับปรุง</span>
                <?php } ?>
            </td>
            <td class="um-col-action">
                <a href="court_edit.php?court_id=<?php echo $row['court_id']; ?>" class="btn-edit">EDIT</a>
                |
                <form method="post" action="court_delete.php" class="form-inline-block"
                      onsubmit="return confirm('ยืนยันการลบสนาม <?php echo $row['court_name']; ?>?')">
                    <input type="hidden" name="court_id" value="<?php echo $row['court_id']; ?>">
                    <button type="submit" class="btn-delete">DELETE</button>
                </form>
            </td>
        </tr>
        <?php } } ?>
    </table>
</div>
</html>

<?php require_once "footer.php"; ?>
