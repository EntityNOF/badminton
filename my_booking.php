<?php
require_once "header.php";
require_once "config.php";

// ต้อง login ก่อน
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$uname = $_SESSION['username'];

// สร้างโฟลเดอร์ uploads ถ้ายังไม่มี
if (!is_dir("uploads")) {
    mkdir("uploads");
}

// แสดง flash message
if (isset($_SESSION['book_msg'])) {
    echo "<p class='msg-success'>" . $_SESSION['book_msg'] . "</p>";
    unset($_SESSION['book_msg']);
}
if (isset($_SESSION['book_err'])) {
    echo "<p class='msg-error'>" . $_SESSION['book_err'] . "</p>";
    unset($_SESSION['book_err']);
}

// === อัปโหลดสลิป ===
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'upload_slip') {
    $booking_id = intval($_POST['booking_id']);

    // เช็คว่าเป็นของ user นี้
    $chk = mysqli_query($connect, "SELECT * FROM booking WHERE booking_id=$booking_id AND username='$uname'");
    if (mysqli_num_rows($chk) == 0) {
        $_SESSION['book_err'] = "ไม่พบการจองนี้";
        header("Location: my_booking.php");
        exit;
    }

    if (isset($_FILES['slip']) && $_FILES['slip']['error'] == 0) {
        $ext      = pathinfo($_FILES['slip']['name'], PATHINFO_EXTENSION);
        $filename = "slip_" . $booking_id . "_" . time() . "." . $ext;
        move_uploaded_file($_FILES['slip']['tmp_name'], "uploads/" . $filename);

        mysqli_query($connect, "UPDATE booking SET slip_image='$filename' WHERE booking_id=$booking_id");
        $_SESSION['book_msg'] = "อัปโหลดสลิปสำเร็จ";
    } else {
        $_SESSION['book_err'] = "กรุณาเลือกไฟล์สลิป";
    }
    header("Location: my_booking.php");
    exit;
}

// === ยกเลิกการจอง ===
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'cancel') {
    $booking_id = intval($_POST['booking_id']);

    // เช็คว่าเป็นของ user นี้ และยังไม่ได้จ่าย
    $chk = mysqli_query($connect, "SELECT * FROM booking WHERE booking_id=$booking_id AND username='$uname'");
    $row = mysqli_fetch_assoc($chk);

    if (!$row) {
        $_SESSION['book_err'] = "ไม่พบการจองนี้";
    } elseif ($row['payment_status'] == 'paid') {
        $_SESSION['book_err'] = "ไม่สามารถยกเลิกได้ เนื่องจากชำระเงินแล้ว";
    } else {
        // ลบไฟล์สลิปด้วยถ้ามี
        if ($row['slip_image'] && file_exists("uploads/" . $row['slip_image'])) {
            unlink("uploads/" . $row['slip_image']);
        }
        mysqli_query($connect, "DELETE FROM booking WHERE booking_id=$booking_id");
        $_SESSION['book_msg'] = "ยกเลิกการจองเรียบร้อยแล้ว";
    }
    header("Location: my_booking.php");
    exit;
}

// ดึงการจองทั้งหมดของ user
$result = mysqli_query($connect, "SELECT * FROM booking WHERE username='$uname' ORDER BY booking_date DESC, time_slot ASC");
?>

<html>
<link rel="stylesheet" href="style.css">
<div class="my-booking-wrap">
    <h2>การจองของฉัน</h2>
    <p><a href="court_booking.php">← กลับไปหน้าจองสนาม</a></p>

    <?php if (mysqli_num_rows($result) == 0) { ?>
        <p>ยังไม่มีการจอง</p>
    <?php } else { ?>
    <table class="my-booking-table">
        <tr class="tbl-header">
            <th>#</th>
            <th>วันที่</th>
            <th>เวลา</th>
            <th>สนาม</th>
            <th>ราคา (บาท)</th>
            <th>สถานะการจ่ายเงิน</th>
            <th>สลิป</th>
            <th>จัดการ</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td class="td-pad"><?php echo $row['booking_id']; ?></td>
            <td class="td-pad"><?php echo $row['booking_date']; ?></td>
            <td class="td-pad"><?php echo $row['time_slot']; ?></td>
            <td class="td-pad"><?php echo $row['court_number']; ?></td>
            <td class="td-pad"><?php echo number_format($row['price_per_slot'], 2); ?></td>
            <td class="td-pad">
                <?php
                if ($row['payment_status'] == 'paid') {
                    echo "<span class='text-green'>ชำระแล้ว</span>";
                } else {
                    echo "<span class='text-orange'>รอชำระ</span>";
                }
                ?>
            </td>
            <td class="td-pad">
                <?php if ($row['slip_image']) { ?>
                    <a href="uploads/<?php echo $row['slip_image']; ?>" target="_blank">ดูสลิป</a>
                <?php } else { ?>
                    -
                <?php } ?>
            </td>
            <td class="td-pad">
                <?php if ($row['payment_status'] == 'paid') { ?>
                    <span class="text-green">เสร็จสิ้น</span>
                <?php } else { ?>
                    <?php if (!$row['slip_image']) { ?>
                    <!-- multipart/form-data" เพื่อ ให้อัปโหลดรูปภาพได้ -->
                    <form method="post" action="my_booking.php" enctype="multipart/form-data" class="form-inline-block">
                        <input type="hidden" name="action"     value="upload_slip">
                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                        <input type="file"   name="slip" accept="image/*" class="slip-input">
                        <br>
                        <button type="submit" class="btn-upload">อัปโหลดสลิป</button>
                    </form>
                    <?php } else { ?>
                        <span class="text-muted">อัปโหลดแล้ว รอตรวจสอบ</span>
                    <?php } ?>
                    <form method="post" action="my_booking.php" class="form-inline-block">
                        <input type="hidden" name="action"     value="cancel">
                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                        <button type="submit" class="btn-cancel">ยกเลิก</button>
                    </form>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
    <?php } ?>
</div>
</html>
<?php require_once "footer.php"; ?>
