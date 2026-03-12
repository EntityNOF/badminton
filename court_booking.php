<?php
require_once "header.php";
require_once "config.php";

// รับวันที่ที่เลือก ถ้าไม่มีก็ใช้วันนี้
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// แสดง flash message (จาก submit)
if (isset($_SESSION['book_msg'])) {
    echo "<p class='msg-success'>" . $_SESSION['book_msg'] . "</p>";
    unset($_SESSION['book_msg']);
}
if (isset($_SESSION['book_err'])) {
    echo "<p class='msg-error'>" . $_SESSION['book_err'] . "</p>";
    unset($_SESSION['book_err']);
}

// ดึงข้อมูลที่จองแล้ว (ยกเว้นที่ยกเลิก)
$booked = [];
$result = mysqli_query($connect, "SELECT time_slot, court_number FROM booking WHERE booking_date='$selected_date' AND payment_status != 'cancelled'");
while ($row = mysqli_fetch_assoc($result)) {
    $booked[$row['court_number']][$row['time_slot']] = true;
}

// ดึงข้อมูลสนาม (court) เพื่อแสดงชื่อและราคา
$courts = [];
$courtResult = mysqli_query($connect, "SELECT * FROM court WHERE is_active=1 ORDER BY court_number ASC");
while ($c = mysqli_fetch_assoc($courtResult)) {
    $courts[$c['court_number']] = $c;
}

$time_slots = ['08:00-09:00','09:00-10:00','10:00-11:00','11:00-12:00',
               '12:00-13:00','13:00-14:00','14:00-15:00','15:00-16:00',
               '16:00-17:00','17:00-18:00','18:00-19:00','19:00-20:00',
               '20:00-21:00','21:00-22:00'];
?>

<html>
<link rel="stylesheet" href="style.css">
<div class="booking-wrap">
    <h2>จองสนามแบดมินตัน</h2>

    <!-- เลือกวันที่ -->
    <form method="get" action="court_booking.php" id="date-form">
        เลือกวันที่:
        <input type="date" name="date" value="<?php echo $selected_date; ?>"
        onchange="document.getElementById('date-form').submit()"> <!-- javascript ในการ force submit form เพื่อให้ตารางเปลี่ยนตามวันที่เลือก -->
    </form>
    <br>
    <p>วันที่เลือก: <b><?php echo $selected_date; ?></b></p>

    <?php if (isset($_SESSION['username'])) { ?>
    <p><a href="my_booking.php">ดูการจองของฉัน</a></p>
    <?php } ?>

    <!-- ตารางเวลา -->
    <table class="booking-table">
        <tr>
            <th>เวลา</th>
            <?php foreach ($courts as $cn => $cd) { ?>
            <th><?php echo $cd['court_name']; ?><br><small><?php echo number_format($cd['price_per_hour'], 0); ?> บาท/ชม.</small></th>
            <?php } ?>
        </tr>
        <?php foreach ($time_slots as $slot) { ?>
        <tr>
            <td><?php echo $slot; ?></td>
            <?php foreach ($courts as $c => $cd) {
                $is_booked = isset($booked[$c][$slot]);
            ?>
            <td class="booking-table-cell">
                <?php 
                if ($is_booked) { 
                    echo '<button disabled class="btn-booked">จองแล้ว</button>';
                } elseif (isset($_SESSION['username'])) { 
                    echo "<form method='post' action='court_booking_submit.php' class='form-inline'>
                            <input type='hidden' name='time_slot' value='{$slot}'>
                            <input type='hidden' name='book_date' value='{$selected_date}'>
                            <input type='hidden' name='court_number' value='{$c}'>
                            <button type='submit' class='btn-book'>จอง</button>
                          </form>";
                } else { 
                    echo '<button disabled class="btn-available">ว่าง</button>';
                } 
                ?>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>

    <?php if (!isset($_SESSION['username'])) { ?>
    <p class="login-hint"><a href="login.php">เข้าสู่ระบบ</a> เพื่อจองสนาม</p>
    <?php } ?>
</div>
</html>
<?php require_once "footer.php"; ?>
