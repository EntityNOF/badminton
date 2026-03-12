<?php
require_once "header.php";
require_once "config.php";

if (!isset($_SESSION['username']) || $_SESSION['level'] != 3) {
    header("Location: index.php");
    exit;
}

// flash message
$msg = isset($_SESSION['book_msg']) ? $_SESSION['book_msg'] : '';
$err = isset($_SESSION['book_err']) ? $_SESSION['book_err'] : '';
unset($_SESSION['book_msg'], $_SESSION['book_err']);

// กรองสถานะ
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

if ($status == 'all') {
    $bookings = mysqli_query($connect, "SELECT * FROM booking ORDER BY booking_date DESC");
} else {
    $bookings = mysqli_query($connect, "SELECT * FROM booking WHERE payment_status='$status' ORDER BY booking_date DESC");
}

// นับ
$cnt_all       = mysqli_num_rows(mysqli_query($connect, "SELECT booking_id FROM booking"));
$cnt_pending   = mysqli_num_rows(mysqli_query($connect, "SELECT booking_id FROM booking WHERE payment_status='pending'"));
$cnt_paid      = mysqli_num_rows(mysqli_query($connect, "SELECT booking_id FROM booking WHERE payment_status='paid'"));
$cnt_cancelled = mysqli_num_rows(mysqli_query($connect, "SELECT booking_id FROM booking WHERE payment_status='cancelled'"));
?>

<html>
<link rel="stylesheet" href="style.css">

<?php if ($msg) { echo "<p class='msg-success'>$msg</p>"; } ?>
<?php if ($err) { echo "<p class='msg-error'>$err</p>"; } ?>

<div class="bm-wrap">

    <h2 class="bm-title">จัดการการจอง</h2>

    <!-- ปุ่มกรอง -->
    <div class="bm-filter">
        <a href="booking_management.php"><button class="bm-filter-btn">ทั้งหมด (<?php echo $cnt_all; ?>)</button></a>
        <a href="booking_management.php?status=pending"><button class="bm-filter-btn">รอชำระ (<?php echo $cnt_pending; ?>)</button></a>
        <a href="booking_management.php?status=paid"><button class="bm-filter-btn">ชำระแล้ว (<?php echo $cnt_paid; ?>)</button></a>
        <a href="booking_management.php?status=cancelled"><button class="bm-filter-btn">ยกเลิก (<?php echo $cnt_cancelled; ?>)</button></a>

        <form method="post" action="bm_delete_all.php" class="form-inline-block"
              onsubmit="return confirm('ยืนยันลบทั้งหมด?')">
            <button type="submit" class="btn-delete-all">ลบทั้งหมด</button>
        </form>
    </div>

    <!-- ตาราง -->
    <?php if (mysqli_num_rows($bookings) == 0) { ?>
        <p>ไม่พบรายการจอง</p>
    <?php } else { ?>
    <table class="bm-table">
        <tr>
            <th>#</th>
            <th>ผู้จอง</th>
            <th>วันที่</th>
            <th>เวลา</th>
            <th>สนาม</th>
            <th>ราคา (บาท)</th>
            <th>สถานะ</th>
            <th>สลิป</th>
            <th>เปลี่ยนสถานะ</th>
            <th>ลบ</th>
        </tr>
        <?php while ($b = mysqli_fetch_assoc($bookings)) { ?>
        <tr>
            <td class="bm-td"><?php echo $b['booking_id']; ?></td>
            <td class="bm-td-left"><?php echo $b['username']; ?></td>
            <td class="bm-td"><?php echo $b['booking_date']; ?></td>
            <td class="bm-td"><?php echo $b['time_slot']; ?></td>
            <td class="bm-td"><?php echo $b['court_number']; ?></td>
            <td class="bm-td"><?php echo number_format($b['price_per_slot'], 2); ?></td>
            <td class="bm-td">
                <?php
                if ($b['payment_status'] == 'paid') {
                    echo "<span class='text-green'>ชำระแล้ว</span>";
                } elseif ($b['payment_status'] == 'cancelled') {
                    echo "<span class='text-gray'>ยกเลิก</span>";
                } else {
                    echo "<span class='text-orange'>รอชำระ</span>";
                }
                ?>
            </td>
            <td class="bm-td">
                <?= $b['slip_image'] ? "<a href='uploads/{$b['slip_image']}' target='_blank'>ดูสลิป</a>" : "-" ?>
            </td>
            <td class="bm-td">
                <form method="post" action="bm_change_status.php" class="form-inline">
                    <input type="hidden" name="booking_id" value="<?php echo $b['booking_id']; ?>">
                    <input type="hidden" name="redirect" value="booking_management.php?status=<?php echo $status; ?>">
                    <select name="new_status">
                        <?php 
                        $statuses = ['pending' => 'รอชำระ', 'paid' => 'ชำระแล้ว', 'cancelled' => 'ยกเลิก'];
                        foreach ($statuses as $val => $label) {
                            $selected = ($b['payment_status'] == $val) ? 'selected' : '';
                            echo "<option value='$val' $selected>$label</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" value="บันทึก">
                </form>
            </td>
            <td class="bm-td">
                <form method="post" action="bm_delete_one.php" class="form-inline">
                    <input type="hidden" name="booking_id" value="<?php echo $b['booking_id']; ?>">
                    <input type="hidden" name="redirect" value="booking_management.php?status=<?php echo $status; ?>">
                    <input type="submit" value="ลบ" class="btn-delete-sm">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    <?php } ?>

</div>
</html>
<?php require_once "footer.php"; ?>